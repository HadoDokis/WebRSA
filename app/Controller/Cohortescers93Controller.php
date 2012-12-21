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
	 * La classe Cohortescers93Controller permet ...
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
				'saisie',
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
		public $helpers = array( 'Csv', 'Default2', 'Search', 'Xform', 'Xhtml', 'Cake1xLegacy.Ajax', 'Checkboxes' );

		/**
		 * Modèles utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $uses = array( 'Cohortecer93', 'Contratinsertion', 'Option' );

		/**
		 * Moteur de recherche et traitement des enregistrements ligne par ligne
		 * (Ajax) pour la partie "2. Saisie d'un CER".
		 *
		 * @return void
		 */
		public function saisie() {
			$this->Workflowscers93->assertUserCi();
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId();

			$this->_traitercohorteajax( $structurereferente_id, '01signe' );
		}

		/**
		 * Moteur de recherche pour la partie "3. Validation Responsable".
		 *
		 * @return void
		 */
		public function avalidercpdv() {
			$this->Workflowscers93->assertUserCpdv();

			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId();
			$this->_traitercohorteajax( $structurereferente_id, '02attdecisioncpdv' );
		}

		/**
		 * Moteur de recherche et de traitement Ajax commun pour les étapes
		 * "2. Saisie d'un CER" et "3. Validation Responsable".
		 *
		 * @param integer $structurereferente_id
		 * @param string $position
		 */
		protected function _traitercohorteajax( $structurereferente_id, $position ) {
			$options = $this->_indexOptions( $structurereferente_id );

			if( $this->action == 'saisie' ) {
				$histochoixcer93key = 'Histochoixcer93etape02';
			}
			else {
				$histochoixcer93key = 'Histochoixcer93';
			}

			if( !$this->request->is( 'ajax' ) ) {
				// On doit pouvoir abtenir les résultats dès le premier accès à la page
				if( !isset( $this->request->data['Search'] ) ) {
					$this->request->data = Set::merge(
						$this->Filtresdefaut->values(),
						array( 'Search' => array( 'active' => true ) )
					);
				}

				$querydata = $this->_qd( $this->request->data['Search'] );

				$this->paginate = $querydata;
				$cers93 = $this->paginate(
					$this->Contratinsertion->Personne,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);
				$cers93 = $this->_addCommentairenormecer93( $cers93, $histochoixcer93key );

				$dossiers_ids = Set::extract( $cers93, "/Cer93[positioncer={$position}]/../Dossier/id" );
				$this->Cohortes->get( $dossiers_ids );

				$this->set( compact( 'cers93', 'options' ) );

				if( $this->action == 'avalidercpdv' ) {
					// INFO: l'équivalent d'une fonction prepareFormDataAvaliderCpdv()
					$commentairesnormescers93_indexes_ids = array_keys( $options['commentairesnormescers93_list'] );
					$requestData = $this->request->data;
					$nbCommentairesnormescers93 = count( $commentairesnormescers93_indexes_ids );

					foreach( $cers93 as $index => $cer93 ) {
						$requestData['Histochoixcer93'][$index]['formeci'] = $cer93['Histochoixcer93']['formeci'];
						if( isset( $cer93['Commentairenormecer93Histochoixcer93'] ) && !empty( $cer93['Commentairenormecer93Histochoixcer93'] ) ) {
							foreach( $cer93['Commentairenormecer93Histochoixcer93'] as $commentaire ) {

								$j = ( $index * $nbCommentairesnormescers93 ) + array_search( $commentaire['commentairenormecer93_id'], $commentairesnormescers93_indexes_ids );
								$requestData['Commentairenormecer93']['Commentairenormecer93'][$j] = array(
									'commentairenormecer93_id' => $commentaire['commentairenormecer93_id'],
									'commentaireautre' => $commentaire['commentaireautre'],
								);
							}
						}
					}

					$this->request->data = $requestData;
				}
			}
			else {
				// TODO: avec les $keys etc ...
				$formData = Set::extract( $this->request->data, '/Histochoixcer93' );

				$key = array_keys( $this->request->data['Histochoixcer93'] );
				$key = $key[0];

				// TODO: array_move_key( array( 0 => 'Test' ), 0, 2 )
				if( $key != 0 ) {
					$formData[$key] = $formData[0];
					unset( $formData[0] );
				}

				if( isset( $this->request->data['Commentairenormecer93'] ) ) {
					$formData[$key]['Commentairenormecer93'] = $this->request->data['Commentairenormecer93'];
				}

				$this->Contratinsertion->begin();

				if( $this->action == 'saisie' ) {
					$success = $this->Contratinsertion->Cer93->Histochoixcer93->saveAll( $formData, array( 'validate' => 'first', 'atomic' => false ) );
					$success = $success && $this->Contratinsertion->Cer93->updateAll(
						array( '"Cer93"."positioncer"' => "'02attdecisioncpdv'" ),
						array( '"Cer93"."id"' => $formData[$key]['Histochoixcer93']['cer93_id'] )
					);
				}
				else if( $this->action == 'avalidercpdv' ) {
					$success = $this->Contratinsertion->Cer93->Histochoixcer93->saveAll( $formData, array( 'validate' => 'first', 'atomic' => false ) );
					$success = $success && $this->Contratinsertion->Cer93->updateAll(
						array( '"Cer93"."positioncer"' => "'".$this->request->data['decision']."'" ),
						array( '"Cer93"."id"' => $formData[$key]['Histochoixcer93']['cer93_id'] )
					);

					if( $this->request->data['decision'] == '99rejetecpdv' ) {
						$this->Contratinsertion->Cer93->id = $formData[$key]['Histochoixcer93']['cer93_id'];
						$contratinsertion_id = $this->Contratinsertion->Cer93->field( 'contratinsertion_id' );
						$success = $success && $this->Contratinsertion->updateAll(
							array(
								'"Contratinsertion"."decision_ci"' => "'R'",
								'"Contratinsertion"."datedecision"' => "'".date( 'Y-m-d' )."'",
							),
							array( '"Contratinsertion"."id"' => $contratinsertion_id )
						);
					}
				}

				if( $success ) {
					$this->Contratinsertion->commit();
					$dossier_id = $this->Contratinsertion->Cer93->dossierId( $formData[$key]['Histochoixcer93']['cer93_id'] );
					$this->Cohortes->release( $dossier_id );
				}
				else {
					$this->Contratinsertion->rollback();
				}

				$querydata = $this->_qd();
				$querydata['conditions']['Cer93.id'] = $formData[$key]['Histochoixcer93']['cer93_id'];
				$querydata['limit'] = 1;

				$cers93 = $this->Contratinsertion->Personne->find( 'all', $querydata );
				$cers93 = $this->_addCommentairenormecer93( $cers93, $histochoixcer93key );

				if( $key != 0 ) {
					$cers93[$key] = $cers93[0];
					unset( $cers93[0] );
				}

				$this->set( compact( 'cers93', 'options' ) );

				$this->layout = null;
				$this->render( "{$this->action}_tbody_trs" );
			}
		}

		// TODO: à mettre dans le modèle
		protected function _qd( $search = array() ) {
			$querydata = $this->Cohortecer93->search(
				$this->action,
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$search,
				$this->Cohortes->sqLocked( 'Dossier' )
			);

			// TODO: différencier CER / CER précédent ?
			if( $this->action == 'saisie' ) {
				$querydata['conditions'][] = array(
					'OR' => array(
						'Cer93.positioncer' => array( '00enregistre', '01signe', '02attdecisioncpdv' ),
						'Contratinsertion.id IS NULL',
						'Contratinsertion.df_ci <= DATE_TRUNC( \'day\', NOW() )',
						'Contratinsertion.df_ci - INTERVAL \''.Configure::read( 'Cohortescers93.saisie.periodeRenouvellement' ).'\' <= DATE_TRUNC( \'day\', NOW() )'
					),
					'PersonneReferent.referent_id' => $this->Session->read( 'Auth.User.referent_id' )
				);
			}
			else if( $this->action == 'avalidercpdv' ) {
				$querydata['conditions'][] = array(
					'Cer93.positioncer' => '02attdecisioncpdv',
				);
			}

			return $querydata;
		}

		/**
		 *
		 * @param integer $structurereferente_id
		 * @return array
		 */
		protected function _indexOptions( $structurereferente_id ) {
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
				'typevoie' => $this->Option->typevoie(),
				'gestionnaire' => ClassRegistry::init( 'User' )->find( 'list', array( 'fields' => array( 'User.nom_complet' ) ) )
			);
			$options = Set::merge(
				$options,
				$this->Contratinsertion->Cer93->enums(),
				$this->Contratinsertion->Cer93->Histochoixcer93->enums()
			);

			// TODO: à factoriser
			$commentairesnormescers93 = $this->Contratinsertion->Cer93->Histochoixcer93->Commentairenormecer93->find(
				'all',
				array(
					'order' => array( 'Commentairenormecer93.isautre ASC', 'Commentairenormecer93.name ASC' )
				)
			);

			$options['commentairesnormescers93_list'] = Hash::combine( $commentairesnormescers93, '{n}.Commentairenormecer93.id', '{n}.Commentairenormecer93.name' );
			$options['commentairesnormescers93_autres_ids'] = Hash::extract( $commentairesnormescers93, '{n}.Commentairenormecer93[isautre=1].id' );

			return $options;
		}

		/**
		 *
		 * TODO: dans le modèle ?
		 *
		 * @param array $results
		 * @param string $histochoixcer93key
		 * @return array
		 */
		protected function _addCommentairenormecer93( $results, $histochoixcer93key ) {
			if( !empty( $results ) ) {
				foreach( $results as $i => $result ) {
					if( !empty( $result[$histochoixcer93key]['id'] ) ) {
						$commentaires = $this->Contratinsertion->Cer93->Histochoixcer93->Commentairenormecer93Histochoixcer93->find(
							'all',
							array(
								'conditions' => array(
									'Commentairenormecer93Histochoixcer93.histochoixcer93_id' => $result[$histochoixcer93key]['id']
								),
								'contain' => array(
									'Commentairenormecer93'
								),
								'order' => array( 'Commentairenormecer93.isautre ASC', 'Commentairenormecer93.name ASC' )
							)
						);
						$results[$i]['Commentairenormecer93'] = Hash::extract( $commentaires, '{n}.Commentairenormecer93' );
						$results[$i]['Commentairenormecer93Histochoixcer93'] = Hash::extract( $commentaires, '{n}.Commentairenormecer93Histochoixcer93' );
					}
				}
			}

			return $results;
		}

		/**
		 * Moteur de recherche pour la partie "4. Décision CG - 4.1 Première lecture".
		 *
		 * TODO: traitement ligne par ligne également
		 *
		 * @return void
		 */
		public function premierelecture() {
			$this->Workflowscers93->assertUserCg();
			$this->_validations( false );
		}

		/**
		 * Moteur de recherche pour la partie "4. Décision CG - 4.2 Validation CS".
		 *
		 * TODO: traitement ligne par ligne également
		 *
		 * @return void
		 */
		public function validationcs() {
			$this->Workflowscers93->assertUserCg();
			$this->_validations( false );
		}

		/**
		 * Moteur de recherche pour la partie "4. Décision CG - 4.3 Validation Cadre".
		 *
		 * TODO: traitement ligne par ligne également
		 *
		 * @return void
		 */
		public function validationcadre() {
			$this->Workflowscers93->assertUserCg();
			$this->_validations( false );
		}

		/**
		 * Moteur de recherche pour la partie "5. Tableau de suivi".
		 *
		 * @return void
		 */
		public function visualisation() {
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );

			$this->_index( $structurereferente_id );
		}

		/**
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * TODO: renommer cette fonction en _validationcg, du coup, on n'a plus la SR
		 *
		 * @param boolean $checkStructurereferente L'utilisateur doit-il être attaché à une structure référente ?
		 * @return void
		 */
		protected function _validations( $checkStructurereferente ) {
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( $checkStructurereferente );

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
				else if( $this->action == 'visualisation' ) {

					// Valeur pour déterminer si on peut voir ou non les commentaires CG
					// On masque les informations si l'utilisateur est <> CG
					$authUser = $this->Session->read( 'Auth.User.id' );
					$user = ClassRegistry::init( 'User' )->find(
						'first',
						array(
							'conditions' => array(
								'User.id' => $authUser
							),
							'contain' => false
						)
					);
					$authViewCommentaireCg = false;
					if( $user['User']['type'] == 'cg' ) {
						$authViewCommentaireCg = true;
					}


					foreach( $cers93 as $i => $cer93 ){

						// Colonne Non orienté PDV
						$typeOrientationId = $cer93['Orientstruct']['typeorient_id'];
						$typeOrientSociopro = Configure::read( 'Orientstruct.typeorientprincipale.Socioprofessionnelle' );
						if( !empty( $typeOrientationId ) ) {
							//	Nous ne sommes pas en sociopro (PDV)
							if( !in_array( $typeOrientationId, $typeOrientSociopro ) ) {
								$labelTypeOrientation = 'Réorientation';
							}
							else {
								$labelTypeOrientation = '';
							}
						}
						else {
							$labelTypeOrientation = 'Orientation';
						}
						$cers93[$i]['Cer93']['nonorientepdv'] = $labelTypeOrientation;

						// Colonne Saisie du CER
						if( in_array( $cer93['Cer93']['positioncer'], array( '', '00enregistre', '01signe', '02attdecisioncpdv' ) ) ) {
							$positionAvantCG = $cer93['Cer93']['positioncer'];
						}
						else {
							$positionAvantCG = '';
						}
						$cers93[$i]['Cer93']['positioncer_avantcg'] = $positionAvantCG;

						// Colonne Etape du Responsable
						$validationcpdv = '';
						if( !empty( $cer93['Histochoixcer93etape03']['etape'] ) ) {
							if( $cer93['Histochoixcer93etape03']['isrejet'] != '1' ) {
								$validationcpdv = '03attdecisioncg';
							}
							else {
								$validationcpdv = '99rejetecpdv';
							}
						}
						$cers93[$i]['Cer93']['validationcpdv'] = $validationcpdv;

						// On masque les commentaires du CG si l'utilisateur n'est pas du CG
						$commentaireCg = '';
						if( !empty( $cer93['Histochoixcer93etape04']['etape'] ) ) {
							if( $authViewCommentaireCg ) {
								$commentaireCg = $cer93['Histochoixcer93etape04']['commentaire'];
							}
							else {
								$commentaireCg = '';
							}
						}
						$cers93[$i]['Histochoixcer93etape04']['commentaire'] = $commentaireCg;

						if( in_array( $cer93['Cer93']['positioncer'], array( '99valide', '99rejete' ) ) ) {
							$commentaireCpdv = '';
						}
						else {
							$commentaireCpdv = $cer93['Histochoixcer93etape03']['commentaire'];
						}
						$cers93[$i]['Histochoixcer93etape03']['commentaire'] = $commentaireCpdv;
					}
					$this->set( 'cers93', $cers93 );
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
				'typevoie' => $this->Option->typevoie(),
				'gestionnaire' => ClassRegistry::init( 'User' )->find( 'list', array( 'fields' => array( 'User.nom_complet' ) ) )
			);
			$options = Set::merge(
				$options,
				$this->Contratinsertion->Cer93->enums(),
				$this->Contratinsertion->Cer93->Histochoixcer93->enums()
			);

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
		 * TODO
		 *
		 * @return void
		 */
		public function exportcsv( $etape ) {
			if( $etape == 'saisie' ) {
				$this->Workflowscers93->assertUserCi();
			}
			else if( $etape == 'avalidercpdv' ) {
				$this->Workflowscers93->assertUserCpdv();
			}
			else if( in_array( $etape, array( 'premierelecture', 'validationcs', 'validationcadre' ) ) ) {
				$this->Workflowscers93->assertUserCg();
			}
			// TODO: en faire quelque chose ?
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );

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

			// TODO: factoriser ?
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

			$options = Set::merge(
				$options,
				$this->Contratinsertion->Cer93->enums(),
				$this->Contratinsertion->Cer93->Histochoixcer93->enums()
			);

			$this->set( compact( 'options', 'etape', 'cers93' ) );
			$this->layout = '';
		}
	}
?>