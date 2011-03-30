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
					'Dossierep.etapedossierep',
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
			$commissionep = $this->Dossierep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id,
						//'Commissionep.finalisee IS NULL'
					),
					'contain' => array(
						'Ep' => array(
							'Zonegeographique'
						)
					)
				)
			);

			if( !empty( $commissionep['Commissionep']['finalisee'] ) ) {
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
				// Début TODO: à déplacer dans le modèle ?
				$this->Dossierep->begin();
				$data = Set::extract( $this->data, '/Dossierep' );

				$inEp = array();
				$notInEp = array();
				foreach( $data as $dossier ) {
					if( !empty( $dossier['Dossierep']['chosen'] ) ) {
						$inEp[] = $dossier['Dossierep']['id'];
					}
					else {
						$notInEp[] = $dossier['Dossierep']['id'];
					}
				}

				$success = true;
				if( !empty( $notInEp ) ) {
					$success = $this->Dossierep->updateAll(
						array(
							'Dossierep.commissionep_id' => null,
							'Dossierep.etapedossierep' => '\'cree\''
						),
						array( '"Dossierep"."id" IN ( \''.implode( '\', \'', $notInEp ).'\' )' )
					) && $success;

				}

				if( !empty( $inEp ) ) {
					$success = $this->Dossierep->updateAll(
						array(
							'Dossierep.commissionep_id' => $commissionep_id,
							'Dossierep.etapedossierep' => '\'seance\''
						),
						array( '"Dossierep"."id" IN ( \''.implode( '\', \'', $inEp ).'\' )' )
					) && $success;

				}
				// Fin TODO: à déplacer dans le modèle ?

				$this->_setFlashResult( 'Save', $success );

				if( $success ) {
					$this->Dossierep->commit();
					$this->redirect( array( 'controller'=>'commissionseps', 'action'=>'view', $commissionep_id, '#dossiers' ) );
				}
				else {
					$this->Dossierep->rollback();
				}
			}

			$themes = $this->Dossierep->Commissionep->themesTraites($commissionep_id);
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
							'Dossierep.commissionep_id',
							'Dossierep.created',
							'Dossierep.themeep',
						),
						'contain' => array(
							'Commissionep' => array(
								'Ep'
							)
						),
						'joins' => array(
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
							'NOT' => array(
								'Dossierep.etapedossierep = \'decisionep\'',
								'Dossierep.etapedossierep = \'decisioncg\'',
								'Dossierep.etapedossierep = \'traite\'',
							),
							$conditionsAdresses,
							$listeThemes
						),
						'limit' => 100,
						'order' => array( 'Dossierep.created ASC' )
					)
				);

				$dossierseps = $this->paginate( $this->Dossierep );

				// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
				if( empty( $this->data ) ) {
					foreach( $dossierseps as $key => $dossierep ) {
						$dossierseps[$key]['Dossierep']['chosen'] =  ( ( $dossierep['Dossierep']['commissionep_id'] == $commissionep_id ) );
					}
				}

				$options = $this->Dossierep->enums();
				$options['Dossierep']['commissionep_id'] = $this->Dossierep->Commissionep->find(
					'list',
					array(
						'conditions' => array(
							'Commissionep.finalisee' => null
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
			foreach ($themeTraite as $themeName=>$decision) {
				$classThemeName = Inflector::classify($themeName);
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
				$this->set( compact( 'themeName' ) );
			}
			if (!empty($this->data)) {
				$this->Dossierep->begin();
				if ($this->Dossierep->sauvegardeUnique( $dossierep_id, $this->data, $niveauDecision )) {
					$this->_setFlashResult( 'Save', true );
					//$this->Dossierep->rollback();
					$this->Dossierep->commit();
					$this->redirect(array('controller'=>'commissionseps', 'action'=>'traitercg', $dossierep['Dossierep']['commissionep_id']));
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
						'Dossierep.etapedossierep',
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
						'Dossierep.etapedossierep' => 'traite',
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