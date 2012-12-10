<?php
	/**
	 * Code source de la classe Cohortescers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortescers93Controller permet d'assigner un référent du parcours aux allocataires qui
	 * n'en possèdent pas.
	 *
	 * @package app.Controller
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
				'avalidercpdv',
				'premierelecture',
				'validationcs',
				'validationcadre'
			),
			'Search.Filtresdefaut' => array(
				'saisie',
				'avalidercpdv',
				'premierelecture',
				'validationcs',
				'validationcadre',
				'visualisation'
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
					'premierelecture' => array(
						'filter' => 'Search'
					),
					'validationcs' => array(
						'filter' => 'Search'
					),
					'validationcadre' => array(
						'filter' => 'Search'
					),
					'visualisation' => array(
						'filter' => 'Search'
					)
				)
			),
			'Workflowscers93'
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
		 * Cohorte des CERs au sein d'une structure référente (PDV).
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @param boolean $checkStructurereferente L'utilisateur doit-il être attaché à une structure référente ?
		 * @return void
		 */
		 protected function _affichage( $checkStructurereferente ) {
			 $structurereferente_id = $this->Workflowscers93->getStructurereferenteId( true, $checkStructurereferente );

			$this->_index( $structurereferente_id );
		}

		/**
		 * ....
		 * Fonction pour la saisie des CERs (avant entré dans la partie validation/décision
		 *
		 * @return void
		 */
		public function saisie() {
			$this->_affichage( true );
		}

		/**
		 * ....
		 * Fonction pour la visualisation des CERs (après gestion par le workflow)
		 *
		 * @return void
		 */
		public function visualisation() {
			$this->_affichage( false );
		}

		/**
		 * ....
		 * Fonction pour la validation CPDV, première lecture
		 *
		 * @return void
		 */
		public function avalidercpdv() {
			$this->_validations( true );
		}

		/**
		 * ....
		 * Fonction pour la validation CG, première lecture
		 *
		 * @return void
		 */
		public function premierelecture() {
			$this->_validations( false );
		}

		/**
		 * ....
		 * Fonction pour la validation CS, seconde lecture
		 *
		 * @return void
		 */
		public function validationcs() {
			$this->_validations( false );
		}

		/**
		 * ....
		 * Fonction pour la validation cadre
		 *
		 * @return void
		 */
		public function validationcadre() {
			$this->_validations( false );
		}

		/**
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @param boolean $checkStructurereferente L'utilisateur doit-il être attaché à une structure référente ?
		 * @return void
		 */
		protected function _validations( $checkStructurereferente ) {
			$structurereferente_id = $this->Workflowscers93->getStructurereferenteId( true, $checkStructurereferente );

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
				if( ( $this->action != 'saisie' ) && isset( $this->request->data['Histochoixcer93'] ) ) {
					$dossiers_ids = array_unique( Set::extract( '/Histochoixcer93/dossier_id', $this->request->data ) );
					$this->Cohortes->get( $dossiers_ids );

					// On change les règles de validation du modèle PersonneReferent avec celles qui sont spécfiques à la cohorte
					$histochoixcer93Validate = $this->Contratinsertion->Cer93->Histochoixcer93->validate;
					
					$datas = array();
					foreach( $this->request->data['Histochoixcer93'] as $i => $tmp ) {
						if( $tmp['action'] === 'Valider' ) {
							$datas[$i] = array( 'Histochoixcer93' => $tmp );
						}
					}

// 					$datas = Set::extract( '/Histochoixcer93[action=Valider]', $this->request->data );

					if( $this->Contratinsertion->Cer93->Histochoixcer93->saveAll( $datas, array( 'validate' => 'only', 'atomic' => false ) ) ) {
						$this->Contratinsertion->Cer93->Histochoixcer93->begin();

						if( !empty( $datas ) ) {

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
					( ( $this->action != 'saisie' ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
				);

				if( !empty( $structurereferente_id ) ) {
					$querydata['conditions']['Referent.structurereferente_id'] = $structurereferente_id;
				}


				$this->paginate = $querydata;
				$cers93 = $this->paginate(
					$this->Contratinsertion->Personne,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);
				$this->set( 'cers93', $cers93 );

				if( !in_array( $this->action, array( 'saisie', 'visualisation' ) ) ) {
					$this->Cohortes->get( array_unique( Set::extract( '/Dossier/id', $cers93 ) ) );

					// Par défaut, on récupère les informations déjà saisies en individuel
					if( !isset( $this->request->data['Histochoixcer93'] ) ) {
						if( $this->action == 'avalidercpdv' ) {
							$etape = '03attdecisioncg';
						}
						else if( $this->action == 'premierelecture' ) {
							$etape = '04premierelecture';
						}
						else if( $this->action == 'validationcs' ) {
							$etape = '05secondelecture';
						}
						else if( $this->action == 'validationcadre' ) {
							$etape = '06attaviscadre';
						}
						$datas = $this->Cohortecer93->prepareFormData( $cers93, $etape, $this->Session->read( 'Auth.User.id' ) );
						if( !empty( $datas ) ) {
							$this->request->data['Histochoixcer93'] = $datas['Histochoixcer93'];
						}
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
				'formeci' => $this->Option->forme_ci(),
				'typevoie' => $this->Option->typevoie()
			);
			$options = Set::merge( $options, $this->Contratinsertion->Cer93->enums(), $this->Contratinsertion->Cer93->Histochoixcer93->enums() );
// 			debug($options);
			$this->set( compact( 'options' ) );

			if( $this->action == 'saisie' ) {
				$this->render( 'saisie' );
			}
			else if( $this->action == 'avalidercpdv' ) {
				$this->render( 'avalidercpdv' );
			}
			else if( $this->action == 'premierelecture' ) {
				$this->render( 'premierelecture' );
			}
			else if( $this->action == 'visualisation' ) {
				$this->render( 'visualisation' );
			}
		}


		/**
		 * @return void
		 */
		public function exportcsv( $etape ) {
			$structurereferente_id = $this->Workflowscers93->getStructurereferenteId( true, false );

			$data = Xset::bump( $this->request->params['named'], '__' );
			$querydata = $this->Cohortecer93->search(
				$etape,
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$data['Search'],
				null
			);
			unset( $querydata['limit'] );

			$cers93 = $this->Contratinsertion->Personne->find( 'all', $querydata );

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
			$options = Set::merge( $options, $this->Contratinsertion->Cer93->enums(), $this->Contratinsertion->Cer93->Histochoixcer93->enums() );
			$this->set( compact( 'options' ) );
			$this->layout = '';
			$this->set( 'etape', $etape );
			$this->set( 'cers93', $cers93 );
		}
	}
?>
