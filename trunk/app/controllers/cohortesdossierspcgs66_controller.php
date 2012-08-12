<?php
	class Cohortesdossierspcgs66Controller extends AppController
	{
		public $name = 'Cohortesdossierspcgs66';

		public $uses = array(
			'Cohortedossierpcg66',
			'Dossierpcg66',
			'Zonegeographique',
			'Dossier',
			'Option',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Locale' );

		public $components = array(
			'Prg' => array(
				'actions' => array(
					'enattenteaffectation' => array( 'filter' => 'Search' ),
					'affectes' => array( 'filter' => 'Search' ),
					'aimprimer' => array( 'filter' => 'Search' ),
					'atransmettre' => array( 'filter' => 'Search' )
				)
			)
		);


		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options',  $this->Dossierpcg66->allEnumLists() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'serviceinstructeur', $this->Dossierpcg66->Serviceinstructeur->listOptions() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );


			$this->set( 'qual',  $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);
		}



		/**
		*
		*/

		public function enattenteaffectation() {
			$this->_index( 'Affectationdossierpcg66::enattenteaffectation' );
		}

		/**
		*
		*/

		public function affectes() {
			$this->_index( 'Affectationdossierpcg66::affectes' );
		}

		/**
		*
		*/

		public function aimprimer() {
			$this->_index( 'Affectationdossierpcg66::aimprimer' );
		}

		/**
		*
		*/

		public function atransmettre() {
			$this->_index( 'Affectationdossierpcg66::atransmettre' );
		}

		/**
		*
		*/

		protected function _index( $statutAffectation = null ) {
			$this->assert( !empty( $statutAffectation ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
// debug($this->data);
				/**
				*
				* Sauvegarde
				*
				*/

				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Dossierpcg66'] ) ) {

					$valid = $this->Dossierpcg66->saveAll( $this->data['Dossierpcg66'], array( 'validate' => 'only', 'atomic' => false ) );


					if( $valid ) {
						$this->Dossierpcg66->begin();
						$saved = $this->Dossierpcg66->saveAll( $this->data['Dossierpcg66'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							// FIXME ?
							foreach( array_unique( Set::extract( $this->data, 'Dossierpcg66.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->Dossierpcg66->commit();
							unset( $this->data['Dossierpcg66'] );
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
						}
						else {
							$this->Dossierpcg66->rollback();
						}
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutAffectation == 'Affectationdossierpcg66::enattenteaffectation' ) || ( $statutAffectation == 'Affectationdossierpcg66::affectes' ) || ( $statutAffectation == 'Affectationdossierpcg66::aimprimer' ) || ( $statutAffectation == 'Affectationdossierpcg66::atransmettre' ) && !empty( $this->data ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$this->paginate = $this->Cohortedossierpcg66->search( $statutAffectation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$this->paginate['limit'] = 10;
					$cohortedossierpcg66 = $this->paginate( 'Dossierpcg66' );

					if( empty( $this->data['Dossierpcg66'] ) ) {
						// Si un précédent dossier existe, on récupère le gesitonnaire précédent par défaut
						foreach( $cohortedossierpcg66 as $i => $dossierpcg66 ){
							$foyer = $this->Dossierpcg66->Foyer->find(
								'first',
								array(
									'conditions' => array(
										'Foyer.id' => $dossierpcg66['Dossierpcg66']['foyer_id']
									),
									'contain' => array(
										'Dossierpcg66' => array(
											'limit' => 1,
											'fields' => array( 'Dossierpcg66.user_id' ),
											'order' => 'Dossierpcg66.created DESC',
											'conditions' => array(
												'Dossierpcg66.user_id IS NOT NULL'
											)
										)
									)
								)
							);
							$this->data['Dossierpcg66'][$i]['user_id'] = @$foyer['Dossierpcg66'][0]['user_id'];
// 							debug( $foyer );
						}
						
					}

					$this->Dossier->commit();

					$this->set( 'cohortedossierpcg66', $cohortedossierpcg66 );

				}

			}

			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}


			switch( $statutAffectation ) {
				case 'Affectationdossierpcg66::enattenteaffectation':
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Affectationdossierpcg66::affectes':
					$this->render( $this->action, null, 'visualisation' );
					break;
				case 'Affectationdossierpcg66::aimprimer':
					$this->render( $this->action, null, 'aimprimer' );
					break;
				case 'Affectationdossierpcg66::atransmettre':
					$this->render( $this->action, null, 'atransmettre' );
					break;
			}
		}


        /**
        * Export du tableau en CSV
        */

        public function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

            $querydata = $this->Cohortedossierpcg66->search( 'Affectationdossierpcg66::affectes', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $dossierspcgs66 = $this->Dossierpcg66->find( 'all', $querydata );

// debug($dossierspcgs66);
// die();
            $this->_setOptions();
            $this->layout = '';
            $this->set( compact( 'dossierspcgs66' ) );
        }
       
		/**
		* Génération de la cohorte des convocations de passage en commission d'EP aux allocataires.
		*/

		public function notificationsCohorte( ) {
			$this->Dossierpcg66->begin();

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

            $querydata = $this->Cohortedossierpcg66->search( 'Affectationdossierpcg66::aimprimer', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            
            $dossierspcgs66 = $this->Dossierpcg66->find( 'all', $querydata );
// debug($dossierspcgs66);
// die();
			$pdfs = array();
			$decisionsdossierspcgs66_ids = Set::extract( '/Decisiondossierpcg66/id', $dossierspcgs66 );

			foreach( $decisionsdossierspcgs66_ids as $decisiondossierpcg66_id ) {
				$pdfs[] = $this->Dossierpcg66->Decisiondossierpcg66->getPdfDecision( $decisiondossierpcg66_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'NotificationsDecisions' );
			if( $pdfs ) {
				$success = $this->Dossierpcg66->Decisiondossierpcg66->updateDossierpcg66Dateimpression( $decisionsdossierspcgs66_ids );
				if( !$success ) {
					$pdfs = null;
				}
			}

			if( $pdfs ) {
				$this->Dossierpcg66->commit();
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'NotificationsDecisions' );
			}
			else {
				$this->Dossierpcg66->rollback();
				$this->Session->setFlash( 'Impossible de générer les décisions des dossiers PCGs.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>