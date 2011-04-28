<?php
	App::import( 'Core', 'Sanitize' );

	class DossiersepsController extends AppController
	{
		public $helpers = array( 'Default' );

		var $uses = array( 'Option', 'Dossierep', 'Decisionpdo', 'Propopdo' );

		/**
		* FIXME: evite les droits
		*/

		protected function _setOptions() {
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );

			$options = $this->Propopdo->allEnumLists();
			
			// Ajout des enums pour les thématiques du CG uniquement
			foreach( $this->Dossierep->Passagecommissionep->Commissionep->Ep->Regroupementep->themes() as $theme ) {
				/*$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}*/

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->enums() );
				}
			}

			$this->set( compact( 'options' ) );
		}

		public function beforeFilter() {
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
					'Commissionep.dateseance',
					'Dossierep.created',
					'Dossierep.etatdossierep',
					'Dossierep.themeep',
				),
				'contain' => array(
					'Commissionep',
					'Personne',
				),
				'limit' => 10
			);

			$this->set( 'options', $this->Dossierep->enums() );
			$this->set( 'dossierseps', $this->paginate( $this->Dossierep ) );
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

			if( in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'decisionep', 'decisioncg', 'annulee' ) ) ) {
				$this->Session->setFlash( 'Impossible d\'attribuer des dossiers à une commission d\'EP lorsque celle-ci comporte déjà des avis ou des décisions.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}


			$conditionsAdresses = array( 'OR' => array() );
			// Début conditions zones géographiques CG 93
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
			// Fin conditions zones géographiques CG 93
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

			if( !empty( $this->data ) ) {
				$ajouts = array();
				$suppressions = array();
				foreach( $this->data['Dossierep'] as $key => $dossierep ) {
					if( empty( $dossierep['chosen'] ) && !empty( $this->data['Passagecommissionep'][$key]['id'] ) ) {
						$suppressions[] = $this->data['Passagecommissionep'][$key]['id'];
					}
					else if( !empty( $dossierep['chosen'] ) && empty( $this->data['Passagecommissionep'][$key]['id'] ) ) {
						$ajouts[] = array(
// 							'etatdossierep' => 'cree',
							'commissionep_id' => $commissionep_id,
							'dossierep_id' => $dossierep['id'],
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
					$this->redirect( array( 'controller'=>'commissionseps', 'action'=>'view', $commissionep_id, '#dossiers' ) );
				}
				else {
					$this->Dossierep->rollback();
				}
			}

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

				$this->paginate = array(
					'Dossierep' => array(
						'fields' => array(
							'Dossierep.id',
							'Personne.qual',
							'Personne.nom',
							'Personne.prenom',
							'Commissionep.dateseance',
							'Passagecommissionep.id',
							'Passagecommissionep.commissionep_id',
							'Passagecommissionep.dossierep_id',
							'Dossierep.created',
							'Dossierep.themeep',
						),
						'joins' => array(
							array(
								'table'      => 'passagescommissionseps',
								'alias'      => 'Passagecommissionep',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Dossierep.id = Passagecommissionep.dossierep_id',
									'Passagecommissionep.commissionep_id' => $commissionep_id
								)
							),
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
								'table'      => 'personnes',
								'alias'      => 'Personne',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Dossierep.personne_id = Personne.id' )
							),
							array(
								'table'      => 'foyers',
								'alias'      => 'Foyer',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Personne.foyer_id = Foyer.id' )
							),
							array(
								'table'      => 'adressesfoyers',
								'alias'      => 'Adressefoyer',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
							),
							array(
								'table'      => 'adresses',
								'alias'      => 'Adresse',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
							),
						),
						'conditions' => array(
							$conditionsAdresses,
							$listeThemes,
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
							.' )'
						),
						'limit' => 100,
						'order' => array( 'Dossierep.created ASC' )
					)
				);

				$dossierseps = $this->paginate( $this->Dossierep );

				// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
				if( empty( $this->data ) ) {
					foreach( $dossierseps as $key => $dossierep ) {
						$dossierseps[$key]['Dossierep']['chosen'] =  ( ( $dossierep['Passagecommissionep']['commissionep_id'] == $commissionep_id ) );
					}
				}

				$options = $this->Dossierep->enums();
				$options['Dossierep']['commissionep_id'] = $this->Dossierep->Passagecommissionep->Commissionep->find(
					'list',
					array(
						'conditions' => array(
							'Commissionep.etatcommissionep' => array( 'cree', 'associe', 'decisionep' )
						)
					)
				);

			}
			else {
				$this->set( 'themeEmpty', true );
			}

			$this->set( compact( 'options', 'dossierseps', 'commissionep' ) );
			$this->set( 'commissionep_id', $commissionep_id);
		}

		public function decisioncg ( $dossierep_id ) {
			$this->_decision( $dossierep_id, 'cg' );
		}

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

			if (!empty($this->data)) {
				$this->Dossierep->begin();
				if ($this->Dossierep->sauvegardeUnique( $dossierep_id, $this->data, $niveauDecision )) {
					$this->_setFlashResult( 'Save', true );
// 					$this->Dossierep->rollback();
					$this->Dossierep->commit();
					$this->redirect(array('controller'=>'commissionseps', 'action'=>'traitercg', $dossierep['Passagecommissionep'][0]['commissionep_id']));
				}
				else {
					$this->_setFlashResult( 'Save', false );
					$this->Dossierep->rollback();
				}
			}
			else {
				$this->data = $this->Dossierep->prepareFormDataUnique($dossierep_id, $dossierep, $niveauDecision);
			}
			$this->_setOptions();
			$this->set('dossierep_id', $dossierep_id);
		}

		/**
		*
		*/

		public function decisions() {
			$this->paginate = array(
				'Dossierep' => array(
					/*'fields' => array(
						'Dossierep.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Commissionep.dateseance',
						'Dossierep.created',
						'Dossierep.etatdossierep',
						'Dossierep.themeep',
					),*/
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
						// FIXME: pour chaque thème .... + jointure ?
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
				'Defautinsertionep66' => $this->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->enumList( 'decision' ),
				'Saisinebilanparcoursep66' => $this->Dossierep->Saisinebilanparcoursep66->Decisionsaisinebilanparcoursep66->enumList( 'decision' ),
				// CG 93
				'Nonrespectsanctionep93' => $this->Dossierep->Nonrespectsanctionep93->Decisionnonrespectsanctionep93->enumList( 'decision' ),
				'Reorientationep93' => $this->Dossierep->Reorientationep93->Decisionreorientationep93->enumList( 'decision' )
			);
// debug( $decisions );
			$this->set( compact( 'decisions' ) );
			$this->set( 'options', $this->Dossierep->enums() );
			$this->set( 'dossierseps', $this->paginate( $this->Dossierep ) );
		}
	}
?>