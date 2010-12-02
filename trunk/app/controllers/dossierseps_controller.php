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
					'Seanceep.dateseance',
					'Dossierep.created',
					'Dossierep.etapedossierep',
					'Dossierep.themeep',
				),
				'contain' => array(
					'Seanceep',
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

		public function choose( $seanceep_id ) {
			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id,
						'Seanceep.finalisee IS NULL'
					),
					'contain' => array(
						'Ep' => array(
							'Zonegeographique'
						)
					)
				)
			);

			$conditionsAdresses = array( 'OR' => array() );
			// Début conditions zones géographiques CG 93
			if( Configure::read( 'CG.cantons' ) == false ) {
				$zonesgeographiques = Set::extract(
					$seanceep,
					'Ep.Zonegeographique.{n}.codeinsee'
				);
				foreach( $zonesgeographiques as $zonegeographique ) {
						$conditionsAdresses['OR'][] = "Adresse.numcomptt ILIKE '%".Sanitize::paranoid( $zonegeographique )."%'";
				}
			}
			// Fin conditions zones géographiques CG 93
			// Début conditions zones géographiques CG 66
			else {
			/// Critères sur l'adresse - canton
				$zonesgeographiques = Set::extract(
					$seanceep,
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
							'Dossierep.seanceep_id' => null,
							'Dossierep.etapedossierep' => '\'cree\''
						),
						array( 'Dossierep.id IN ( \''.implode( '\', \'', $notInEp ).'\' )' )
					) && $success;

				}

				if( !empty( $inEp ) ) {
					$success = $this->Dossierep->updateAll(
						array(
							'Dossierep.seanceep_id' => $seanceep_id,
							'Dossierep.etapedossierep' => '\'seance\''
						),
						array( 'Dossierep.id IN ( \''.implode( '\', \'', $inEp ).'\' )' )
					) && $success;

				}
				// Fin TODO: à déplacer dans le modèle ?

				$this->_setFlashResult( 'Save', $success );

				if( $success ) {
					$this->Dossierep->commit();
				}
				else {
					$this->Dossierep->rollback();
				}
			}
			
			$themes = $this->Dossierep->Seanceep->themesTraites($seanceep_id);
			$listeThemes['OR'] = array();
			foreach($themes as $theme=>$niveauDecision) {
				$listeThemes['OR'] = array('Dossierep.themeep'=>Inflector::tableize($theme));
			}

			$this->paginate = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Seanceep.dateseance',
					'Dossierep.seanceep_id',
					'Dossierep.created',
					'Dossierep.themeep',
				),
				'contain' => array(
					'Seanceep' => array(
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
				'limit' => 10,
				'order' => array( 'Dossierep.created ASC' )
			);

			$dossierseps = $this->paginate( $this->Dossierep );

			// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
			if( empty( $this->data ) ) {
				foreach( $dossierseps as $key => $dossierep ) {
					$dossierseps[$key]['Dossierep']['chosen'] =  ( ( $dossierep['Dossierep']['seanceep_id'] == $seanceep_id ) );
				}
			}

			$options = $this->Dossierep->enums();
			$options['Dossierep']['seanceep_id'] = $this->Dossierep->Seanceep->find(
				'list',
				array(
					'conditions' => array(
						'Seanceep.finalisee' => null
					)
				)
			);

			$this->set( compact( 'options', 'dossierseps', 'seanceep' ) );
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
					$this->redirect(array('controller'=>'seanceseps', 'action'=>'traitercg', $dossierep['Dossierep']['seanceep_id']));
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
	}
?>
