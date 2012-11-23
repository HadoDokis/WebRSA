<?php
	/**
	 * Code source de la classe DossiersepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Core', 'Sanitize' );

	/**
	 * La classe DossiersepsController ...
	 *
	 * @package app.Controller
	 */
	class DossiersepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Csv', 'Type2' );

		public $uses = array( 'Option', 'Dossierep', 'Decisionpdo', 'Propopdo' );
		public $components = array( 'Gedooo.Gedooo' );

		/**
		* FIXME: evite les droits
		*/

		protected function _setOptions() {
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );

			$options = $this->Propopdo->allEnumLists();

			// Ajout des enums pour les thématiques du CG uniquement
			foreach( $this->Dossierep->Passagecommissionep->Commissionep->Ep->Regroupementep->themes() as $theme ) {
				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->enums() );
				}
			}

			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Dossierep.created',
					'Dossierep.themeep',
				),
				'contain' => array(
					'Personne',
				),
				'limit' => 10
			);

			$this->set( 'options', $this->Dossierep->enums() );
			$this->set( 'dossierseps', $this->paginate( $this->Dossierep ) );
		}

		/**
		 * Envoi à la vue pour chacune des thématiques la liste des dossiers sélectionnables pour
		 * passage en commission d'une commission d'ep donnée.
		 *
		 * Set les variables $themeEmpty, $dossiers, $themesChoose, $countDossiers,
		 * $duree_engag_cg93, $options, $dossierseps, $commissionep, $commissionep_id dans la vue.
		 *
		 * @param array $commissionep L'enregistrement de la commission d'EP
		 * @param boolean $paginate Soit on pagine (pour le choose), soit on find tout, pour l'export CSV
		 */
		protected function _setListeDossiersSelectionnables( $commissionep, $paginate ) {
			$commissionep_id = $commissionep['Commissionep']['id'];

			$conditionsAdresses = array( 'OR' => array() );
			// Début conditions zones géographiques CG 58 et CG 93
			if( Configure::read( 'CG.cantons' ) == false ) {
				$zonesgeographiques = Set::extract(
					$commissionep,
					'Ep.Zonegeographique.{n}.codeinsee'
				);
				if( !empty( $zonesgeographiques ) ) {
					foreach( $zonesgeographiques as $zonegeographique ) {
							$conditionsAdresses['OR'][] = "Adresse.numcomptt ILIKE '%".Sanitize::paranoid( $zonegeographique )."%'";
					}
				}
			}
			// Fin conditions zones géographiques CG 58 et CG 93
			// Début conditions zones géographiques CG 66
			else {
			/// Critères sur l'adresse - canton
				$zonesgeographiques = Set::extract(
					$commissionep,
					'Ep.Zonegeographique.{n}.id'
				);

				$this->Canton = ClassRegistry::init( 'Canton' );
				$conditionsAdresses = array();
				if( count( $zonesgeographiques ) != $this->Canton->Zonegeographique->find( 'count' ) ) {
					$conditionsAdresses = $this->Canton->queryConditionsByZonesgeographiques( $zonesgeographiques );
				}
			}
			// Fin conditions zones géographiques CG 66

			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$listeThemes = null;
			if( !empty( $themes ) ) {
				$listeThemes['OR'] = array();
				foreach($themes as $theme => $niveauDecision) {
					$listeThemes['OR'][] = array( 'Dossierep.themeep' => Inflector::tableize( $theme ) );
				}
				$this->set( 'themeEmpty', false );

				if( empty( $conditionsAdresses['OR'] ) ) {
					$conditionsAdresses = array();
				}


				$conditionsTime = array();
				if( Configure::read( 'Cg.departement' ) == 66 ){
					$delaiAvantSelection = Configure::read( 'Dossierep.delaiavantselection' );
					if( !empty( $delaiAvantSelection ) ) {
						$conditionsTime = array(
							'Dossierep.id IN (
								SELECT
									dossierseps.id
								FROM
									dossierseps
									WHERE
										date_trunc( \'day\', dossierseps.created ) <= ( DATE( NOW() ) - INTERVAL \''.$delaiAvantSelection.'\' )
							)'
						);
					}
				}

				$queryData = array(
					'fields' => array(
						'Dossierep.id',
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Foyer.enerreur',
						'Commissionep.dateseance',
						'Passagecommissionep.id',
						'Passagecommissionep.commissionep_id',
						'Passagecommissionep.dossierep_id',
						'Dossierep.created',
						'Dossierep.themeep',
					),
					'joins' => array(
						array(
							'table'      => 'commissionseps',
							'alias'      => 'Commissionep',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Commissionep.id = Passagecommissionep.commissionep_id'
							)
						),
						array(
							'table'      => 'eps',
							'alias'      => 'Ep',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Commissionep.ep_id = Ep.id'
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id',
								'Calculdroitrsa.toppersdrodevorsa' => '1'
							)
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Situationdossierrsa.dossier_id = Dossier.id',
								'Situationdossierrsa.etatdosrsa' => $this->Dossierep->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert()
							)
						),
					),
					'conditions' => array(
						$conditionsAdresses,
						$listeThemes,
						$conditionsTime,
						'Dossierep.id NOT IN ('.
							$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array( 'passagescommissionseps.dossierep_id' ),
									'alias' => 'passagescommissionseps',
									'joins' => array(
										array(
											'table'      => 'commissionseps',
											'alias'      => 'commissionseps',
											'type'       => 'INNER',
											'foreignKey' => false,
											'conditions' => array( 'commissionseps.id = passagescommissionseps.commissionep_id' ),
											'order'      => array( 'commissionseps.dateseance DESC' ),
											'limit'      => 1,

										),
									),
									'conditions' => array(
										'commissionseps.id <> ' => $commissionep_id,
										'passagescommissionseps.etatdossierep <>' => 'reporte'
									)
								)
							)
						.' )',
						///FIXME: à mettre plus tard dans le model de la thématique ?
						'Dossierep.id NOT IN ('.
							$this->Dossierep->Defautinsertionep66->sq(
								array(
									'fields' => array( 'defautsinsertionseps66.dossierep_id' ),
									'alias' => 'defautsinsertionseps66',
									'conditions' => array(
										'defautsinsertionseps66.dateimpressionconvoc IS NULL'
									)
								)
							)
						.' )',
					),
					'limit' => 50,
					'order' => array( 'Dossierep.created ASC' )
				);

				$options = $this->Dossierep->enums();
				$options['Dossierep']['commissionep_id'] = $this->Dossierep->Passagecommissionep->Commissionep->find(
					'list',
					array(
						'conditions' => array(
							'Commissionep.etatcommissionep' => array( 'cree', 'associe', 'valide', 'presence', 'decisionep' )
						)
					)
				);
			}
			else {
				$this->set( 'themeEmpty', true );
			}

			$themesChoose = array_keys( $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id ) );

			$dossiers = array();
			$countDossiers = 0;
			$originalPaginate = $this->paginate;
			foreach( $themesChoose as $theme ) {
				$class = Inflector::classify( $theme );
				$qdListeDossier = $this->Dossierep->{$class}->qdListeDossier( $commissionep_id );

				if ( isset( $qdListeDossier['fields'] ) ) {
					$qd['fields'] = array_merge( $qdListeDossier['fields'], $queryData['fields'] );
				}
				$qd['conditions'] = array_merge( array( 'Dossierep.themeep' => Inflector::tableize( $class ) ), $queryData['conditions'] );

				$qd['joins'] = array_merge( $qdListeDossier['joins'], $queryData['joins'] );
				$qd['contain'] = false;
				$qd['limit'] = $queryData['limit'];
				$qd['order'] = $queryData['order'];

				$this->Dossierep->{$class}->forceVirtualFields = true;

				if( $paginate ) {
					$this->paginate = $qd;
					$dossiers[$theme] = $this->paginate( $this->Dossierep->{$class} );
					// INFO: sinon ne fonctionne pas correctement dans une boucle en CakePHP 2.x
					if( CAKE_BRANCH != '1.2' ) {
						$this->Components->unload( 'Search.ProgressivePaginator' );
						$this->Components->unload( 'Paginator' );
					}
				}
				else {
					$dossiers[$theme] = $this->Dossierep->{$class}->find( 'all', $qd );
				}

				// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
				if( empty( $this->request->data ) ) {
					foreach( $dossiers[$theme] as $key => $dossierep ) {
						$dossiers[$theme][$key]['Passagecommissionep']['chosen'] = ( ( $dossierep['Passagecommissionep']['commissionep_id'] == $commissionep_id ) );
					}
				}
				$countDossiers += count($dossiers[$theme]);
			}
			$this->paginate = $originalPaginate;
			$this->set( compact( 'dossiers', 'themesChoose' ) );
			$this->set( compact( 'countDossiers' ) );

			if ( Configure::read( 'Cg.departement' ) == 93 ) {
				$options = Set::merge(
					$options,
					$this->Dossierep->Nonrespectsanctionep93->enums()
				);
				$options = Set::merge(
					$options,
					$this->Dossierep->Signalementep93->Contratinsertion->enums()
				);
				$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
			}

			if( Configure::read( 'Cg.departement' ) == 58 ){
				$options = Set::merge( $options, $this->Dossierep->Sanctionep58->enums() );
			}

			$this->set( compact( 'options', 'dossierseps', 'commissionep' ) );
			$this->set( 'commissionep_id', $commissionep_id);
		}

		/**
		*
		*/

		public function choose( $commissionep_id ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Zonegeographique'
						)
					)
				)
			);

			// Peut-on travailler à cette étape avec cette commission ?
			if( in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'decisionep', 'decisioncg', 'annulee' ) ) ) {
				$this->Session->setFlash( 'Impossible d\'attribuer des dossiers à une commission d\'EP lorsque celle-ci comporte déjà des avis ou des décisions.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}

			// Enregistrement des cases cochées / décochées
			if( !empty( $this->request->data ) ) {
				$ajouts = array();
				$suppressions = array();
				foreach( $this->request->data['Dossierep'] as $key => $dossierep ) {
					if( empty( $this->request->data['Passagecommissionep'][$key]['chosen'] ) && !empty( $this->request->data['Passagecommissionep'][$key]['id'] ) ) {
						$suppressions[] = $this->request->data['Passagecommissionep'][$key]['id'];
					}
					else if( !empty( $this->request->data['Passagecommissionep'][$key]['chosen'] ) && empty( $this->request->data['Passagecommissionep'][$key]['id'] ) ) {
						$ajouts[] = array(
							'commissionep_id' => $commissionep_id,
							'dossierep_id' => $this->request->data['Dossierep'][$key]['id'],
							'user_id' => $this->Session->read( 'Auth.User.id' )
						);
					}
				}

				$this->Dossierep->begin();

				$success = true;

				if( !empty( $ajouts ) ) {
					$success = $this->Dossierep->Passagecommissionep->saveAll( $ajouts, array( 'atomic' => false ) ) && $success;
				}

				if( !empty( $suppressions ) ) {
					$success = $this->Dossierep->Passagecommissionep->delete( $suppressions ) && $success;
				}

				// Changer l'état de la séance
				$success = $this->Dossierep->Passagecommissionep->Commissionep->changeEtatCreeAssocie( $commissionep_id ) && $success;

				$this->_setFlashResult( 'Save', $success );

				if( $success ) {
					$this->Dossierep->commit();
					$this->redirect( array( 'controller'=>'commissionseps', 'action'=>'view', $commissionep_id, '#dossiers,'.$this->request->data['Choose']['theme'] ) );
				}
				else {
					$this->Dossierep->rollback();
				}
			}

			$this->_setListeDossiersSelectionnables( $commissionep, true );
		}

		/**
		 * Exporte la liste de dossier sélectionnables pour une commission d'EP donnée.
		 *
		 * @param @integer $commissionep_id L'id de la commission
		 */
		public function exportcsv( $commissionep_id ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Zonegeographique'
						)
					)
				)
			);

			$this->_setListeDossiersSelectionnables( $commissionep, false );

			$this->layout = '';
		}

		/**
		*
		*/

		public function decisioncg ( $dossierep_id ) {
			$this->_decision( $dossierep_id, 'cg' );
		}

		/**
		*
		*/

		public function _decision ( $dossierep_id, $niveauDecision ) {
			$themeTraite = $this->Dossierep->themeTraite($dossierep_id);
			$dossierep = array();

			$dossierep = $this->Dossierep->find(
				'first',
				array(
					'conditions' => array(
						'Dossierep.id' => $dossierep_id
					)
				)
			);

			$classThemeName = Inflector::classify( $dossierep['Dossierep']['themeep'] );
			$containQueryData = $this->Dossierep->{$classThemeName}->containQueryData();
			$dossierep = $this->Dossierep->find(
				'first',
				array(
					'conditions' => array(
						'Dossierep.id' => $dossierep_id
					),
					'contain' => $containQueryData
				)
			);

			$this->set( 'dossier', $dossierep );
			$this->set( 'themeName', Inflector::underscore( $classThemeName ) );

			if (!empty($this->request->data)) {
				$this->Dossierep->begin();
				if ($this->Dossierep->sauvegardeUnique( $dossierep_id, $this->request->data, $niveauDecision )) {
					$this->_setFlashResult( 'Save', true );
					$this->Dossierep->commit();
					$this->redirect(array('controller'=>'commissionseps', 'action'=>'traitercg', $dossierep['Passagecommissionep'][0]['commissionep_id']));
				}
				else {
					$this->_setFlashResult( 'Save', false );
					$this->Dossierep->rollback();
				}
			}
			else {
				$this->request->data = $this->Dossierep->prepareFormDataUnique($dossierep_id, $dossierep, $niveauDecision);
			}
			$this->_setOptions();
			$this->set('dossierep_id', $dossierep_id);
			$this->set( 'commissionep_id', $dossierep['Passagecommissionep'][0]['commissionep_id'] );
		}

		/**
		*
		*/

		public function decisions() {
			$this->paginate = array(
				'Dossierep' => array(
					'contain' => array(
						'Commissionep',
						'Personne' => array(
							'Foyer' => array(
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01'
									),
									'Adresse'
								)
							)
						),
						// Thèmes 66
						'Defautinsertionep66' => array(
							'Decisiondefautinsertionep66' => array(
								'order' => 'created DESC',
								'limit' => 1
							)
						),
						'Saisinebilanparcoursep66' => array(
							'Decisionsaisinebilanparcoursep66' => array(
								'order' => 'created DESC',
								'limit' => 1
							)
						),
						'Saisinepdoep66' => array(
							'Decisionsaisinepdoep66' => array(
								'order' => 'created DESC',
								'limit' => 1,
								'Decisionpdo'
							)
						),
						// Thèmes 93
						'Reorientationep93' => array(
							'Decisionreorientationep93' => array(
								'order' => 'created DESC',
								'limit' => 1
							)
						),
						'Nonrespectsanctionep93' => array(
							'Decisionnonrespectsanctionep93' => array(
								'order' => 'created DESC',
								'limit' => 1
							)
						),
					),
					'conditions' => array(
						'Dossierep.commissionep_id IS NOT NULL',
						'Dossierep.etatdossierep' => 'traite',
					),
					'limit' => 10
				)
			);

			// FIXME: plus générique
			$decisions = array(
				// CG 66
				'Defautinsertionep66' => $this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->enumList( 'decision' ),
				'Saisinebilanparcoursep66' => $this->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->enumList( 'decision' ),
				// CG 93
				'Nonrespectsanctionep93' => $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->enumList( 'decision' ),
				'Reorientationep93' => $this->Dossierep->Passagecommissionep->Decisionreorientationep93->enumList( 'decision' )
			);
			$this->set( compact( 'decisions' ) );
			$this->set( 'options', $this->Dossierep->enums() );
			$this->set( 'dossierseps', $this->paginate( $this->Dossierep ) );
		}


		/**
		* Génération et envoi du courrier d'information avant passage en EP pour
		* la thématique defautinsertionep66.
		*/

		public function courrierInformation( $dossierep_id ) {
			$dossierep = $this->Dossierep->find(
				'first',
				array(
					'conditions' => array(
						'Dossierep.id' => $dossierep_id
					)
				)
			);

			$classThemeName = Inflector::classify( $dossierep['Dossierep']['themeep'] );
			$pdf = $this->Dossierep->{$classThemeName}->getCourrierInformationPdf( $dossierep['Dossierep']['id'], $this->Session->read( 'Auth.User.id' ) );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'Courrier_Information.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération des courriers d'information avant passage en EP pour
		* la thématique defautinsertionep66.
		*/

		public function courriersInformations( $commissionep_id ) {
			$liste = $this->Dossierep->Passagecommissionep->find(
				'all',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.dossierep_id',
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => 'defautsinsertionseps66'
					),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			$pdfs = array();
			foreach( Set::extract( '/Passagecommissionep/dossierep_id', $liste ) as $dossierep_id ) {
				$pdfs[] = $this->Dossierep->Defautinsertionep66->getCourrierInformationPdf( $dossierep_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'CourriersInformation' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'CourriersInformation.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les courriers d\'information pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>