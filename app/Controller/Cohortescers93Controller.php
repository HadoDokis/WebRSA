<?php
	/**
	 * Code source de la classe Cohortescers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortescers93Controller permet d'assigner un référent du parcours aux allocataires qui
	 * n'en possèdent pas.
	 *
	 * @package app.controllers
	 */
	class Cohortescers93Controller extends AppController
	{
		/**
		 * Nom du contrôleur
		 *
		 * @var string
		 */
		public $name = 'Cohortescers93';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes' => array(
				'avalidercpdv'
			),
			'Search.Filtresdefaut' => array(
				'saisie',
				'avalidercpdv'
			),
			'Gestionzonesgeos',
			'Search.Prg' => array(
				'actions' => array(
					'saisie' => array(
						'filter' => 'Search'
					),
					'avalidercpdv' => array(
						'filter' => 'Search'
					),
				)
			)
		);

		/**
		 * Helpers utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $helpers = array( 'Csv', 'Default2', 'Search', 'Xform', 'Xhtml' );

		/**
		 * Modèles utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $uses = array( 'Cohortecer93', 'Contratinsertion', 'Option' );

		/**
		 * Cohorte d'affectation des référents au sein d'une structure référente (PDV).
		 * La date de début d'affectation est la date du jour.
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function saisie() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$this->_index( $structurereferente_id );
		}

		/**
		 * ....
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function avalidercpdv() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$referent_id = $this->Session->read( 'Auth.User.referent_id' );
				if( !empty( $referent_id ) ) {
					$this->User->Referent->id = $referent_id;
					$structurereferente_id = $this->User->Referent->field( 'structurereferente_id' );
				}
			}

			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$this->_index( $structurereferente_id );
		}

		/**
		 * Méthode de recherche générique.
		 *
		 * @param integer $structurereferente_id L'id de la structure référente à laquelle l'utilisateur est lié.
		 */
		protected function _index( $structurereferente_id ) {
			if( !empty( $this->request->data ) ) {
			
					// Traitement du formulaire d'affectation
				if( ( $this->action == 'avalidercpdv' ) && isset( $this->request->data['Histochoixcer93'] ) ) {
					$dossiers_ids = array_unique( Set::extract( '/Histochoixcer93/dossier_id', $this->request->data ) );
					$this->Cohortes->get( $dossiers_ids );

					// On change les règles de validation du modèle PersonneReferent avec celles qui sont spécfiques à la cohorte
					$histochoixcer93Validate = $this->Contratinsertion->Cer93->Histochoixcer93->validate;

					$datas = Set::extract( '/Histochoixcer93[action]', $this->request->data );
					if( $this->Contratinsertion->Cer93->Histochoixcer93->saveAll( $datas, array( 'validate' => 'only', 'atomic' => false ) ) ) {
						$this->Contratinsertion->Cer93->Histochoixcer93->begin();

						$datas = Set::extract( '/Histochoixcer93[action=Valider]', $this->request->data );

						if( !empty( $datas ) ) {
// 							debug($datas);
// 							die();
							foreach( $datas as $key => $data ) {
								$saved = $this->Contratinsertion->Cer93->Histochoixcer93->saveDecision( $data );
								
								if( $saved ) {
									$this->Contratinsertion->Cer93->Histochoixcer93->commit();
									$this->Cohortes->release( $dossiers_ids );
									$this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
									unset( $this->request->data['Histochoixcer93'] );	
								}
								else {
									$this->Contratinsertion->Cer93->Histochoixcer93->rollback();
									$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
								}
							}
						}
						else {
							$this->Contratinsertion->Cer93->Histochoixcer93->rollback();

							if( empty( $datas ) ) {
								$this->Session->setFlash( 'Aucun élément à enregistrer', 'flash/notice' );
							}
							else {
								$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
							}
						}
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

					$this->Contratinsertion->Cer93->Histochoixcer93->validate = $histochoixcer93Validate;
				}
				
		
				// Traitement du formulaire de recherche
				$querydata = $this->Cohortecer93->search(
					$this->action,
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data['Search'],
					( ( $this->action == 'avalidercpdv' ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
				);

				$this->paginate = $querydata;
				$cers93 = $this->paginate(
					$this->Contratinsertion->Personne,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);
				$this->set( 'cers93', $cers93 );

				if( $this->action == 'avalidercpdv' ) {
					$this->Cohortes->get( array_unique( Set::extract( '/Dossier/id', $cers93 ) ) );

					// Par défaut, on récupère les informations déjà saisies en individuel
					if( !isset( $this->request->data['Histochoixcer93'] ) ) {
						$datas = $this->Cohortecer93->prepareFormData( $cers93, '03attdecisioncg', $this->Session->read( 'Auth.User.id' ) );
						$this->request->data['Histochoixcer93'] = $datas['Histochoixcer93'];
					}
				}
			}
			


			$this->set( 'structurereferente_id', $structurereferente_id );

			// Options
			$options = array(
				'actions' => array( 'Valider' => 'Valider', 'En attente' => 'En attente' ),
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
				'etatdosrsa' => $this->Option->etatdosrsa(),
				'exists' => array( '1' => 'Oui', '0' => 'Non' ),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'referents' => $this->Contratinsertion->Personne->PersonneReferent->Referent->referentsListe( $structurereferente_id ),
				'toppersdrodevorsa' => $this->Option->toppersdrodevorsa( true ),
				'rolepers' => $this->Option->rolepers(),
				'formeci' => $this->Option->forme_ci()
			);
			$options = Set::merge( $options, $this->Contratinsertion->Cer93->enums() );
			$this->set( compact( 'options' ) );

			if( $this->action == 'saisie' ) {
				$this->render( 'saisie' );
			}
			else {
				$this->render( 'avalidercpdv' );
			}
		}
		
		
		/**
		 * @return void
		 */
		public function exportcsv() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$data = Xset::bump( $this->request->params['named'], '__' );
			$querydata = $this->Cohortecer93->search(
				'saisie',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$data['Search'],
				null
			);
			unset( $querydata['limit'] );

			$cers93 = $this->Contratinsertion->Personne->find( 'all', $querydata );

			$this->layout = '';
			$this->set( 'cers93', $cers93 );
		}
	}
?>
