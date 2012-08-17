<?php
	class CohortesciController extends AppController
	{
		public $name = 'Cohortesci';
		public $uses = array( 'Cohorteci', 'Dossier', 'Option', 'Situationdossierrsa' );

		public $aucunDroit = array( 'constReq', 'ajaxreferent' );

		public $helpers = array( 'Csv', 'Ajax', 'Search', 'Default2' );
		public $paginate = array( 'limit' => 20, );
		public $components = array(
			'Prg2' => array(
				'actions' => array(
					'valides',
					'nouveaux' => array( 'filter' => 'Filtre' ),
					'nouveauxsimple' => array( 'filter' => 'Filtre' ),
					'nouveauxparticulier' => array( 'filter' => 'Filtre' )
				)
			),
			'Jetons'
		);

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

			$this->set( 'rolepers', $this->Option->rolepers() );

			$forme_ci = array();
			if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			}
			$this->set( 'forme_ci', $forme_ci );

			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatOuvert()) );

			return $return;
		}

		/**
		*
		*/

		public function nouveauxparticulier() {
			$this->_index( 'Decisionci::nonvalideparticulier' );
		}

		/**
		*
		*/

		public function nouveauxsimple() {
			$this->_index( 'Decisionci::nonvalidesimple' );
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
				* Sauvegarde
				*/

				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Contratinsertion'] ) ) {
					$contratsatraiter = Set::extract('/Contratinsertion[atraiter=1]', $this->data );
// 	debug($this->data);
// 	die();
					if( !empty( $contratsatraiter ) ){

						if( Configure::read( 'Cg.departement' ) != 66 ) {
							$valid = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $contratsatraiter, array( 'validate' => 'only', 'atomic' => false ) );
							if( $valid ) {
								$this->Dossier->begin();
								$saved = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $contratsatraiter, array( 'validate' => 'first', 'atomic' => false ) );
								if( $saved ) {
									// FIXME ?
									foreach( array_unique( Set::extract( $this->data, 'Contratinsertion.{n}.dossier_id' ) ) as $dossier_id ) {
										$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
									}
									$this->Dossier->commit(); //FIXME
									unset( $this->data['Contratinsertion'] );
								}
								else {
									$this->Dossier->rollback();
								}
							}
						}
						else if( Configure::read( 'Cg.departement' ) == 66 ) {
							$valid = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $contratsatraiter, array( 'validate' => 'only', 'atomic' => false ) );
							if( $valid ) {
								$this->Dossier->begin();

								$saved = $this->Dossier->Foyer->Personne->Contratinsertion->Propodecisioncer66->sauvegardeCohorteCer( $this->data['Contratinsertion'] );

								$saved = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $contratsatraiter, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

								if( $saved ) {
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
					}
				}

				/**
				* Filtrage
				*/

				if( ( $statutValidation == 'Decisionci::nonvalide' ) || ( $statutValidation == 'Decisionci::nonvalidesimple' ) || ( $statutValidation == 'Decisionci::nonvalideparticulier' ) || ( ( $statutValidation == 'Decisionci::valides' ) && !empty( $this->data ) ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$paginate = $this->Cohorteci->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$paginate['limit'] = 10;

					$this->paginate = $paginate;
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

						if( Configure::read( 'Cg.departement' ) == 66 ) {
							if( empty( $value['Contratinsertion']['datedecision'] ) ) {
								$cohorteci[$key]['Contratinsertion']['proposition_datedecision'] = date( 'Y-m-d' );
							}
							else {
								$cohorteci[$key]['Contratinsertion']['proposition_datedecision'] = $value['Contratinsertion']['datedecision'];
							}
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
				case 'Decisionci::nonvalidesimple':
					$this->render( $this->action, null, 'formulairesimple' );
					break;
				case 'Decisionci::nonvalideparticulier':
					$this->render( $this->action, null, 'formulaireparticulier' );
					break;
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

			$querydata = $this->Cohorteci->search( 'Decisionci::valides', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
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