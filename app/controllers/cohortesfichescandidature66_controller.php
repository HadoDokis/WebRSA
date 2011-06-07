<?php
	class Cohortesfichescandidature66Controller extends AppController
	{
		public $name = 'Cohortesfichescandidature66';

		public $uses = array(
			'Cohortefichecandidature66',
			'ActioncandidatPersonne',
			'Actioncandidat',
			'Zonegeographique',
			'Dossier',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2' );

		public $components = array( 'Prg' => array( 'actions' => array( 'fichesenattente' => array( 'filter' => 'Search' ), 'fichesencours' => array( 'filter' => 'Search' ) ) ) );

		/**
		*
		*/
		public function _setOptions() {
			$motifssortie = $this->ActioncandidatPersonne->Motifsortie->find( 'list' );
			$options = Set::merge(
				$this->ActioncandidatPersonne->allEnumLists(),
				$this->ActioncandidatPersonne->Actioncandidat->allEnumLists()
			);
			$options['actions'] = $this->ActioncandidatPersonne->Actioncandidat->find( 'list', array( 'fields' => array( 'name' ) ) );
			$options['partenaires'] = $this->ActioncandidatPersonne->Actioncandidat->Contactpartenaire->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ) ) );
			$options['referents'] = $this->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1 ) );

			$this->set( compact( 'options', 'motifssortie' ) );
		}



		/**
		*
		*/

		public function fichesenattente() {
			$this->_index( 'Suivifiche::fichesenattente' );
		}

		/**
		*
		*/

		public function fichesencours() {
			$this->_index( 'Suivifiche::fichesencours' );
		}


		/**
		*
		*/

		protected function _index( $statutFiche = null ) {
			$this->assert( !empty( $statutFiche ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			if( !empty( $this->data ) ) {

				/**
				*
				* Sauvegarde
				*
				*/
				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['ActioncandidatPersonne'] ) ) {

					$valid = $this->ActioncandidatPersonne->saveAll( $this->data['ActioncandidatPersonne'], array( 'validate' => 'only', 'atomic' => false ) );


					if( $valid ) {
						$this->ActioncandidatPersonne->begin();
						$saved = $this->ActioncandidatPersonne->saveAll( $this->data['ActioncandidatPersonne'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							// FIXME ?
							foreach( array_unique( Set::extract( $this->data, 'ActioncandidatPersonne.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->ActioncandidatPersonne->commit();
						}
						else {
							$this->ActioncandidatPersonne->rollback();
						}
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutFiche == 'Suivifiche::fichesenattente' ) || ( ( $statutFiche == 'Suivifiche::fichesencours' ) && !empty( $this->data ) ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$this->paginate = $this->Cohortefichecandidature66->search( $statutFiche, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data['Search'], $this->Jetons->ids() );
					$this->paginate['limit'] = 10;
					$cohortefichecandidature66 = $this->paginate( 'ActioncandidatPersonne' );

					foreach( $cohortefichecandidature66 as $key => $value ) {


						if( empty( $value['ActioncandidatPersonne']['sortiele'] ) ) {
							$cohortefichecandidature66[$key]['ActioncandidatPersonne']['proposition_sortiele'] = date( 'Y-m-d' );
						}
						else{
							$cohortefichecandidature66[$key]['ActioncandidatPersonne']['proposition_sortiele'] = $value['ActioncandidatPersonne']['sortiele'];
						}

					}

					$this->Dossier->commit();

					$this->set( 'cohortefichecandidature66', $cohortefichecandidature66 );

				}

			}

			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}


			switch( $statutFiche ) {
				case 'Suivifiche::fichesenattente':
					$this->set( 'pageTitle', 'Fiches de candidature en attente' );
					$this->render( $this->action, null, 'formulaireenattente' );
					break;
				case 'Suivifiche::fichesencours':
					$this->set( 'pageTitle', 'Fiches de candidature en cours' );
					$this->render( $this->action, null, 'formulaireencours' );
					break;
			}


		}
	}
?>