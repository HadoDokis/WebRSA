<?php
	/**
	 * Code source de la classe Relancesnonrespectssanctionseps93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once( APPLIBS.'cmis.php' );

	/**
	 * La classe Relancesnonrespectssanctionseps93Controller ...
	 *
	 * @package app.Controller
	 */
	class Relancesnonrespectssanctionseps93Controller extends AppController
	{
		public $uses = array( 'Relancenonrespectsanctionep93', 'Nonrespectsanctionep93', 'Orientstruct', 'Contratinsertion', 'Dossierep', 'Dossier', 'Pdf' );

		public $components = array(
			'Search.Prg' => array(
				'actions' => array(
					'cohorte' => array( 'filter' => 'Search' ),
					'impressions'
				)
			),
			'Gedooo.Gedooo',
			'Cohortes' => array( 'cohorte' ),
			'Jetons2',
			'DossiersMenus'
		);

		public $helpers = array( 'Default2', 'Csv' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'cohorte' => 'read',
			'exportcsv' => 'read',
			'impression' => 'read',
			'impression_cohorte' => 'read',
			'impressions' => 'read',
			'index' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			/// Mise en cache (session) de la liste des codes Insee pour les selects
			/// TODO: Une fonction ?
			/// TODO: Voir où l'utiliser ailleurs
			if( !$this->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
					$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Personne->Cui->Structurereferente->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				}
				else {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee();
				}
				$this->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );
			}
			else {
				$listeCodesInseeLocalites = $this->Session->read( 'Cache.mesCodesInsee' );
			}

			$options = array(
				'Adresse' => array( 'numcomptt' => $listeCodesInseeLocalites ),
				'Serviceinstructeur' => array( 'id' => $this->Orientstruct->Serviceinstructeur->find( 'list' ) )
			);
			$options = Set::merge(
				$options,
				$this->Relancenonrespectsanctionep93->enums(),
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->enums(),
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->enums(),
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->Passagecommissionep->enums()
			);

			$this->set( compact( 'options' ) );
		}

		/**
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ) {
			$this->assert( is_numeric( $personne_id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$erreurs = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );

			$conditions = array( 'OR' => array(), 'Nonrespectsanctionep93.origine' => array( 'orientstruct', 'contratinsertion' ) );

			$orientsstructs = $this->Orientstruct->find(
				'list',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $orientsstructs ) ) {
				$conditions['OR']['Nonrespectsanctionep93.orientstruct_id'] = $orientsstructs;
			}

			$contratsinsertion = $this->Contratinsertion->find(
				'list',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $contratsinsertion ) ) {
				$conditions['OR']['Nonrespectsanctionep93.contratinsertion_id'] = $contratsinsertion;
			}

			$relances = array();
			if( !empty( $conditions['OR'] ) ) {
				$personne = $this->Nonrespectsanctionep93->Orientstruct->Personne->find(
					'first',
					array(
						'conditions' => array(
							'Personne.id' => $personne_id
						),
						'contain' => array(
							'Foyer' => array(
								'Dossier',
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01'
									),
									'Adresse'
								)
							)
						),
					)
				);
				$relances = $this->Nonrespectsanctionep93->find(
					'all',
					array(
						'fields' => array(
							'Orientstruct.id',
							'Orientstruct.date_valid',
							'Contratinsertion.id',
							'Contratinsertion.df_ci',
							'Relancenonrespectsanctionep93.id',
							'Relancenonrespectsanctionep93.numrelance',
							'Relancenonrespectsanctionep93.daterelance',
							'Pdf.id',
						),
						'conditions' => $conditions,
						'joins' => array(
							array(
								'table'      => 'relancesnonrespectssanctionseps93',
								'alias'      => 'Relancenonrespectsanctionep93',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Relancenonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id'
								)
							),
							array(
								'table'      => 'orientsstructs',
								'alias'      => 'Orientstruct',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Orientstruct.id = Nonrespectsanctionep93.orientstruct_id'
								)
							),
							array(
								'table'      => 'contratsinsertion',
								'alias'      => 'Contratinsertion',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Contratinsertion.id = Nonrespectsanctionep93.contratinsertion_id'
								)
							),
							array(
								'table'      => 'pdfs',
								'alias'      => 'Pdf',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Relancenonrespectsanctionep93.id = Pdf.fk_value',
									'Pdf.modele' => 'Relancenonrespectsanctionep93',
								)
							),
						),
						'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC', 'Relancenonrespectsanctionep93.numrelance DESC' )
					)
				);
			}

			$this->set( compact( 'relances', 'erreurs', 'personne' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 * Formulaire d'ajout de relances en cohorte, pour un premier passage.
		 */
		public function cohorte() {
			if( !empty( $this->request->data ) ) {
				$this->request->data = Hash::expand( $this->request->data );
				$search = $this->request->data['Search'];

				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				/// Enregistrement de la cohorte de relances
				if( isset( $this->request->data['Relancenonrespectsanctionep93'] ) ) {
					$data = $this->request->data['Relancenonrespectsanctionep93'];

					// On filtre les relances en attente, on récupère les ids des dossiers pour les jetons
					$dossiersIds = array();
					$newData = array();
					foreach( $data as $i => $relance ) {
						if( is_array( $relance ) ) { // INFO: sinon on prend en compte la clé sessionKey
							if( isset( $relance['dossier_id'] ) ) {
								$dossiersIds[] = $relance['dossier_id'];
							}

							if( isset( $relance['arelancer'] ) && $relance['arelancer'] == 'R' ) {
								$newData[$i] = $relance;
							}
						}
					}

					if( !empty( $newData ) ) {
						$this->Nonrespectsanctionep93->begin();

						// Relances non respect orientation
						$success = $this->Relancenonrespectsanctionep93->saveCohorte( $newData, $search );

						$this->_setFlashResult( 'Save', $success );
						if( $success ) {
							unset( $this->request->data['Relancenonrespectsanctionep93'], $this->request->data['sessionKey'] );
							$this->Nonrespectsanctionep93->commit();
							// On libère les jetons
							$this->Cohortes->release( $dossiersIds );

							$url = Set::merge( array( 'action' => $this->action ), Hash::flatten( $this->request->data ) );
							$this->redirect( $url );
						}
						else {
							$this->Nonrespectsanctionep93->rollback();
						}
					}
					else { // On libère les jetons de toutes façons
						$this->Cohortes->release( $dossiersIds );
					}
				}

				/// Moteur de recherche
				$search = Hash::flatten( $search );
				$search = Hash::filter( (array)$search );

				if( $this->request->data['Search']['Relance']['contrat'] == 0 ) {
					$this->paginate = array(
						'Orientstruct' => $this->Relancenonrespectsanctionep93->search(
							$mesCodesInsee,
							$this->Session->read( 'Auth.User.filtre_zone_geo' ),
							$search,
							$this->Cohortes->sqLocked( 'Dossier' )
						)
					);

					$results = $this->paginate( $this->Nonrespectsanctionep93->Orientstruct );
				}
				else if( $this->request->data['Search']['Relance']['contrat'] == 1 ) {
					$this->paginate = array(
						'Contratinsertion' => $this->Relancenonrespectsanctionep93->search(
							$mesCodesInsee,
							$this->Session->read( 'Auth.User.filtre_zone_geo' ),
							$search,
							$this->Cohortes->sqLocked( 'Dossier' )
						)
					);

					$results = $this->paginate( $this->Nonrespectsanctionep93->Contratinsertion );
				};

				if( !empty( $results ) ) {
					$dossiersIds = Hash::extract( $results, '{n}.Dossier.id' );
					$this->Cohortes->get( $dossiersIds );

					$results = $this->Relancenonrespectsanctionep93->prepareFormData( $results, $search );
				}
				$this->set( compact( 'results' ) );

				if( $this->Relancenonrespectsanctionep93->checkCompareError( Hash::expand( $search ) ) == true ) {
					$this->Session->setFlash( 'Vos critères de recherche entrent en contradiction avec les critères de base', 'flash/error' );
				}
			}

			$this->_setOptions();
		}

		/**
		 * Formulaire d'ajout de relances en individuel, pour un premier passage.
		 *
		 * @param integer $personne_id
		 * @throws NotFoundException
		 * @throws InternalErrorException
		 */
		public function add( $personne_id ) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$dossier_id = Hash::get( $dossierMenu, 'Dossier.id' );

			// On s'assure que l'id passé en paramètre et le dossier lié existent bien
			if( empty( $personne_id ) || empty( $dossier_id ) ) {
				throw new NotFoundException();
			}

			$erreursAjout = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );
			if( !empty( $erreursAjout ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Tentative d'acquisition du jeton sur le dossier
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$success = true;
				$this->Relancenonrespectsanctionep93->begin();

				$nonrespectsanctionep93 = array( 'Nonrespectsanctionep93' => $this->request->data['Nonrespectsanctionep93'] );
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
				$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->save() && $success;

				$relancenonrespectsanctionep93 = array( 'Relancenonrespectsanctionep93' => $this->request->data['Relancenonrespectsanctionep93'] );
				$relancenonrespectsanctionep93['Relancenonrespectsanctionep93']['nonrespectsanctionep93_id'] = $this->Nonrespectsanctionep93->id;
				$this->Relancenonrespectsanctionep93->create( $relancenonrespectsanctionep93 );
				$success = $this->Relancenonrespectsanctionep93->save() && $success;

				// Création du dossier d'EP pour la seconde relance
				if( Hash::get( $this->request->data, 'Relancenonrespectsanctionep93.numrelance' ) == 2 ) {
					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $personne_id,
							'themeep' => 'nonrespectssanctionseps93',
						),
					);
					$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->create( $dossierep );
					$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->save() && $success;

					if( $success ) {
						$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->updateAllUnBound(
							array(
								'"Nonrespectsanctionep93"."sortienvcontrat"' => '\'0\'',
								'"Nonrespectsanctionep93"."active"' => '\'0\'',
								'"Nonrespectsanctionep93"."dossierep_id"' => $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->id,
							),
							array( '"Nonrespectsanctionep93"."id"' => $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->id )
						) && $success;
					}
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Relancenonrespectsanctionep93->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Relancenonrespectsanctionep93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				// On prépare les valeurs par défaut du formulaire; pour cela, on se sert des méthodes existant en cohortes
				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$results = $this->Relancenonrespectsanctionep93->getRelance(
					$personne_id,
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->Cohortes->sqLocked( 'Dossier' ),
					$this->Session->read( 'Auth.user.id' )
				);

				if( !empty( $results ) ) {
					$results = $this->Relancenonrespectsanctionep93->prepareFormData( $results );
					$this->request->data = $this->Relancenonrespectsanctionep93->prepareFormDataAdd( $results[0], $this->Session->read( 'Auth.User.id' ) );
				}
				else {
					throw new InternalErrorException();
				}
			}

			$this->set( compact( 'dossierMenu' ) );
			$this->_setOptions();
		}

		/**
		 *
		 */
		public function impressions() {
			if( !empty( $this->request->data ) ) {
				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);

				$queryData['limit'] = 10;

				$this->Relancenonrespectsanctionep93->forceVirtualFields = true;

				$this->paginate = $queryData;
				$relances = $this->paginate( $this->Relancenonrespectsanctionep93 );

				$this->set( compact( 'relances' ) );
			}

			$this->_setOptions();
		}

		/**
		 *
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);

			$this->Relancenonrespectsanctionep93->forceVirtualFields = true;

			$relances = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );

			$this->layout = '';
			$this->set( compact( 'relances' ) );
			$this->_setOptions();
		}

		/**
		 *
		 * @param integer $id
		 */
		public function impression( $id ) {
			$this->assert( is_numeric( $id ), 'invalidParameter' );

			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Relancenonrespectsanctionep93->personneId( $id ) ) );

			$this->Relancenonrespectsanctionep93->begin();

			$pdf = $this->Relancenonrespectsanctionep93->getStoredPdf( $id, 'dateimpression' );

			if( empty( $pdf ) ) {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->cakeError( 'error404' );
			}
			else if( !empty( $pdf['Pdf']['document'] ) ) {
				$this->Relancenonrespectsanctionep93->commit();
				$this->layout = '';
				$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], sprintf( "relance-%s.pdf", date( "Ymd-H\hi" ) ) );
			}
			else {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->cakeError( 'error500' );
			}

		}

		/**
		 *
		 */
		public function impression_cohorte() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);

			$queryData['fields'] = array(
				'Pdf.document',
				'Pdf.cmspath',
				'Relancenonrespectsanctionep93.id',
				'Relancenonrespectsanctionep93.dateimpression',
			);

			$this->Relancenonrespectsanctionep93->begin();

			$nErrors = 0;
			$contents = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );
			foreach( $contents as $i => $content ) {
				if( empty( $content['Pdf']['document'] ) && !empty( $content['Pdf']['cmspath'] ) ) {
					$cmisPdf = Cmis::read( $content['Pdf']['cmspath'], true );
					if( !empty( $cmisPdf['content'] ) ) {
						$contents[$i]['Pdf']['document'] = $cmisPdf['content'];
					}
				}
				// Gestion des erreurs: si on n'a toujours pas le document
				if( empty( $contents[$i]['Pdf']['document'] ) ) {
					$nErrors++;
					unset( $contents[$i] );
				}
			}

			if( $nErrors > 0 ) {
				$this->Session->setFlash( "Erreur lors de l'impression en cohorte: {$nErrors} documents n'ont pas pu être imprimés. Abandon de l'impression de la cohorte. Demandez à votre administrateur d'exécuter cake/console/cake generationpdfs relancenonrespectsanctionep93", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			$ids = Set::extract( '/Relancenonrespectsanctionep93/id', $contents );
			$pdfs = Set::extract( '/Pdf/document', $contents );

			if( empty( $content['Relancenonrespectsanctionep93']['dateimpression'] ) ) {
				$this->Relancenonrespectsanctionep93->updateAllUnBound(
					array( 'Relancenonrespectsanctionep93.dateimpression' => date( "'Y-m-d'" ) ),
					array( '"Relancenonrespectsanctionep93"."id"' => $ids, '"Relancenonrespectsanctionep93"."dateimpression" IS NOT NULL' )
				);
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'Relancenonrespectsanctionep93' );

			if( !empty( $pdfs ) ) {
				$this->Relancenonrespectsanctionep93->commit();
				$this->layout = '';
				$this->Gedooo->sendPdfContentToClient( $pdfs, sprintf( "cohorterelances-%s.pdf", date( "Ymd-H\hi" ) ) );
			}
			else {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'impression en cohorte.', 'flash/error' );
				$this->redirect( $this->referer() );
			}
		}
	}
?>