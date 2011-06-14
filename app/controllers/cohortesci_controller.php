<?php
	class CohortesciController extends AppController
	{
		public $name = 'Cohortesci';

		public $uses = array( 'Cohorteci', 'Dossier', 'Option', );

		public $aucunDroit = array( 'constReq', 'ajaxreferent' );

		public $helpers = array( 'Csv', 'Ajax' );

		public $paginate = array( 'limit' => 20, );

		public $components = array( 'Prg' => array( 'actions' => array( 'valides', 'nouveaux' => array( 'filter' => 'Filtre' ) ) ), 'Jetons' );

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '512M');
			$return = parent::beforeFilter();
			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'printed', $this->Option->printed() );
			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$struct = $this->Dossier->Foyer->Personne->Contratinsertion->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$this->set( 'struct', $struct );
			$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );

			$this->set( 'numcontrat', $this->Dossier->Foyer->Personne->Contratinsertion->allEnumLists() );

			$forme_ci = array();
			if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			}
			$this->set( 'forme_ci', $forme_ci );
			return $return;
		}


		/**
		*
		*/

		public function nouveaux() {
			$this->_index( 'Decisionci::nonvalide' );
		}

		/**
		*
		*/

		public function valides() {
			$this->_index( 'Decisionci::valides' );
		}

		/**
		*
		*/

		/*public function enattente() {
			$this->_index( 'Decisionci::enattente' );
		}*/

		/**
		*   Ajax pour lien référent - structure référente
		*/

		public function _selectReferents( $structurereferente_id ) {
			$conditions = array();

			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$referents = $this->Dossier->Foyer->Personne->Contratinsertion->Referent->find(
				'all',
				array(
					'conditions' => $conditions,
					'recursive' => -1
				)
			);
			return $referents;

		}

		/**
		*
		*/

		public function ajaxreferent() { // FIXME
			Configure::write( 'debug', 0 );
			$referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Filtre.structurereferente_id' ) );
			$options = array( '<option value=""></option>' );
			foreach( $referents as $referent ) {
				$options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
			} ///FIXME: à mettre dans la vue
			echo implode( '', $options );
			$this->render( null, 'ajax' );
		}


		/**
		*
		*/

		protected function _index( $statutValidation = null ) {
			$this->assert( !empty( $statutValidation ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Dossier->Foyer->Personne->Contratinsertion->Zonegeographique->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$personne_suivi = $this->Dossier->Foyer->Personne->Contratinsertion->find(
				'list',
				array(
					'fields' => array(
						'Contratinsertion.pers_charg_suivi',
						'Contratinsertion.pers_charg_suivi'
					),
					'order' => 'Contratinsertion.pers_charg_suivi ASC',
					'group' => 'Contratinsertion.pers_charg_suivi',
				)
			);

			$this->set( 'personne_suivi', $personne_suivi );

			$params = $this->data;

			if( !empty( $params ) ) {
				/**
				*
				* Sauvegarde
				*
				*/

				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Contratinsertion'] ) ) {
					$valid = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'only', 'atomic' => false ) );
					if( $valid ) {
						$this->Dossier->begin();
						$saved = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'first', 'atomic' => false ) );
						if( $saved ) {
							// FIXME ?
							foreach( array_unique( Set::extract( $this->data, 'Contratinsertion.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->Dossier->commit();
							unset( $this->data['Contratinsertion'] );
						}
						else {
							$this->Dossier->rollback();
						}
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutValidation == 'Decisionci::nonvalide' ) || ( ( $statutValidation == 'Decisionci::valides' ) && !empty( $this->data ) ) /*|| ( ( $statutValidation == 'Decisionci::enattente' ) && !empty( $this->data ) )*/ ) {
					$this->Dossier->begin(); // Pour les jetons

					$this->paginate = $this->Cohorteci->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$this->paginate['limit'] = 10;
					$cohorteci = $this->paginate( $this->Dossier->Foyer->Personne->Contratinsertion );

					$this->Dossier->commit();

					foreach( $cohorteci as $key => $value ) {
						if( $value['Contratinsertion']['decision_ci'] == 'E' && Configure::read( 'nom_form_cg' == 'cg66' ) ) {
							$cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = 'E';
						}
						else if( $value['Contratinsertion']['decision_ci'] == 'E' && Configure::read( 'nom_form_cg' == 'cg93' ) ) {
							$cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = 'V';
						}
						else {
							$cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = $value['Contratinsertion']['decision_ci'];
						}

						if( empty( $value['Contratinsertion']['datevalidation_ci'] ) ) {
							$cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = $value['Contratinsertion']['dd_ci'];
						}
						else {
							$cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = $value['Contratinsertion']['datevalidation_ci'];
						}
					}

					$this->set( 'cohorteci', $cohorteci );
				}

			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Personne->Contratinsertion->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			/// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );
			$referents = $this->Dossier->Foyer->Personne->Contratinsertion->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			switch( $statutValidation ) {
				case 'Decisionci::nonvalide':
					$this->render( $this->action, null, 'formulaire' );
					break;
/*				case 'Decisionci::enattente':
					$this->render( $this->action, null, 'formulaire' );
					break;*/
				case 'Decisionci::valides':
					$this->render( $this->action, null, 'visualisation' );
					break;
			}
		}


		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Cohorteci->search( 'Decisionci::valides', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
			unset( $querydata['limit'] );
			$contrats = $this->Dossier->Foyer->Personne->Contratinsertion->find( 'all', $querydata );

			/// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' );
			$referents = $this->Dossier->Foyer->Personne->Contratinsertion->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			$this->set( 'action', $this->Dossier->Foyer->Personne->Contratinsertion->Actioninsertion->find( 'list' ) );

			$this->layout = '';
			$this->set( compact( 'contrats' ) );
		}
	}
?>
