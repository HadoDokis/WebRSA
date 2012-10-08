<?php
    /**
	 * Code source de la classe Cohortesdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortesdossierspcgs66Controller permet de traiter les dossiers PCGs en cohorte
	 * (CG 66).
	 *
	 * @package app.controllers
	 */
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

		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Locale', 'Search' );

		public $components = array(
			'Prg2' => array(
				'actions' => array(
					'enattenteaffectation' => array( 'filter' => 'Search' ),
					'affectes' => array( 'filter' => 'Search' ),
					'aimprimer' => array( 'filter' => 'Search' ),
					'atransmettre' => array( 'filter' => 'Search' )
				)
			),
			'Gestionzonesgeos',
			'Cohortes' => array(
				'enattenteaffectation',
				'atransmettre'
			)
		);


		/**
		*
		*/
		public function _setOptions() {
			$options = $this->Dossierpcg66->allEnumLists();
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

			$etatdossierpcg = $options['etatdossierpcg'];
			$this->set( compact( 'options', 'etatdossierpcg' ) );
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

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			if( !empty( $this->data ) ) {
// debug($this->data);
				/**
				*
				* Sauvegarde
				*
				*/

				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Dossierpcg66'] ) ) {
                    $this->Cohortes->get( array_unique( Set::extract( $this->data, 'Dossierpcg66.{n}.dossier_id' ) ) );

					$valid = $this->Dossierpcg66->saveAll( $this->data['Dossierpcg66'], array( 'validate' => 'only', 'atomic' => false ) );

					if( $valid ) {
						$this->Dossierpcg66->begin();
						$saved = $this->Dossierpcg66->saveAll( $this->data['Dossierpcg66'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							$this->Dossierpcg66->commit();
                            $this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
                            $this->Cohortes->release( array_unique( Set::extract( $this->data, 'Dossierpcg66.{n}.dossier_id' ) ) );

							unset( $this->data['Dossierpcg66'] );
//							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
						}
						else {
							$this->Dossierpcg66->rollback();
                            $this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						}
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutAffectation == 'Affectationdossierpcg66::enattenteaffectation' ) || ( $statutAffectation == 'Affectationdossierpcg66::affectes' ) || ( $statutAffectation == 'Affectationdossierpcg66::aimprimer' ) || ( $statutAffectation == 'Affectationdossierpcg66::atransmettre' ) && !empty( $this->data ) ) {
					$paginate = $this->Cohortedossierpcg66->search(
                        $statutAffectation,
                        (array)$this->Session->read( 'Auth.Zonegeographique' ),
                        $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                        $this->data,
                        $this->Cohortes->sqLocked( 'Dossier' )
                    );

					$paginate['limit'] = 10;

					$this->paginate = $paginate;
					$cohortedossierpcg66 = $this->paginate( 'Dossierpcg66' );

					if( empty( $this->data['Dossierpcg66'] ) ) {
						// Si un précédent dossier existe, on récupère le gestionnaire précédent par défaut
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
						}
					}

                    if( !in_array( $statutAffectation, array( 'Affectationdossierpcg66::affectes', 'Affectationdossierpcg66::aimprimer' ) ) ) {
						$this->Cohortes->get( array_unique( Set::extract( $cohortedossierpcg66, '{n}.Dossier.id' ) ) );
					}

					$this->set( 'cohortedossierpcg66', $cohortedossierpcg66 );
				}
			}

			$this->_setOptions();

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
			$querydata = $this->Cohortedossierpcg66->search(
				'Affectationdossierpcg66::affectes',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$dossierspcgs66 = $this->Dossierpcg66->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'dossierspcgs66' ) );
		}

		/**
		 * Génération de la cohorte des convocations de passage en commission d'EP aux allocataires.
		 */
		public function notificationsCohorte() {
			$this->Dossierpcg66->begin();

			$querydata = $this->Cohortedossierpcg66->search(
				'Affectationdossierpcg66::aimprimer',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' )
			);
			unset( $querydata['limit'] );

			$dossierspcgs66 = $this->Dossierpcg66->find( 'all', $querydata );

			$pdfs = array( );
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
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'NotificationsDecisions.pdf' );
			}
			else {
				$this->Dossierpcg66->rollback();
				$this->Session->setFlash( 'Impossible de générer les décisions des dossiers PCGs.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

	}
?>