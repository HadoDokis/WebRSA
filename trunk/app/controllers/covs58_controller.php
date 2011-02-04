<?php
	class Covs58Controller extends AppController
	{
		public $name = 'Covs58';
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
			return parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$themescovs58 = $this->Cov58->Dossiercov58->Themecov58->find('list');
			
			$options = $this->Cov58->enums();
			$options = array_merge($options, $this->Cov58->Dossiercov58->enums());
			$typesorients = $this->Cov58->Dossiercov58->Propoorientationcov58->Structurereferente->Typeorient->listOptions();
			$structuresreferentes = $this->Cov58->Dossiercov58->Propoorientationcov58->Structurereferente->list1Options();
			
			$this->set(compact('options', 'typesorients', 'structuresreferentes'));
			
			$decisionscovs = array( 'accepte' => 'Accepté', 'refus' => 'Refusé', 'ajourne' => 'Ajourné' );
			$this->set(compact('decisionscovs'));
		}

		/**
		*
		*/

		public function index() {
			if( !empty( $this->data ) ) {
				$queryData = $this->Cov58->search( $this->data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$covs58 = $this->paginate( $this->Cov58 );
				$this->set( 'covs58', $covs58 );
// 				$this->set( 'etape', $etape );
			}
			$this->_setOptions();
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Cov58->begin();
				$this->Cov58->create( $this->data );
				$success = $this->Cov58->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Cov58->commit();
					$this->redirect( array( 'action' => 'view', $this->Cov58->id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Cov58->find(
					'first',
					array(
						'conditions' => array( 'Cov58.id' => $id ),
						'contain' => false
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
				$this->set('cov58_id', $id);
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}
		
		/**
		*
		*/
		
		public function view( $cov58_id = null ) {
			$cov58 = $this->Cov58->find(
				'first', array(
					'conditions' => array( 'Cov58.id' => $cov58_id ),
					'contain' => false
				)
			);
// 			debug( $seanceep );
			$this->set('cov58', $cov58);
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
			$themes = $this->Cov58->Dossiercov58->Themecov58->find('list');
			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;
			foreach( $themes as $key => $theme ) {
				$class = Inflector::classify( $theme );
				$dossiers[$class] = $this->Cov58->Dossiercov58->find(
					'all',
					array(
						'conditions' => array(
							'Dossiercov58.cov58_id' => $cov58_id,
							'Dossiercov58.themecov58_id' => $key
						),
						'contain' => array(
							$class,
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse'
									)
								)
							)
						),
					)
				);
				$countDossiers += count($dossiers[$class]);
			}
			$this->set(compact('dossiers'));
			$this->set(compact('countDossiers'));
		}

		/**
		*
		*/

		public function choose( $cov58_id ) {
			$cov58 = $this->Cov58->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id,
						'Cov58.etatcov' => 'cree'
					),
					'contain' => false
				)
			);
			
			$themes = array( 'Propoorientationcov58' );

			if( !empty( $this->data ) ) {
				// Début TODO: à déplacer dans le modèle ?
				$this->Cov58->begin();
				
				foreach($themes as $theme) {
					$data = Set::extract( $this->data, '/'.$theme );

					$inCov = array();
					$notInCov = array();
					foreach( $data as $dossier ) {
						if( !empty( $dossier[$theme]['chosen'] ) ) {
							$inCov[] = $dossier[$theme]['id'];
						}
						else {
							$notInCov[] = $dossier[$theme]['id'];
						}
					}
					
					$success = true;
					if( !empty( $notInCov ) ) {
						$success = $this->Cov58->{$theme}->updateAll(
							array(
								$theme.'.cov58_id' => null,
								$theme.'.etapecov' => '\'cree\''
							),
							array( '"'.$theme.'"."id" IN ( \''.implode( '\', \'', $notInCov ).'\' )' )
						) && $success;
					}

					if( !empty( $inCov ) ) {
						$success = $this->Cov58->{$theme}->updateAll(
							array(
								$theme.'.cov58_id' => $cov58_id,
								$theme.'.etapecov' => '\'traitement\''
							),
							array( '"'.$theme.'"."id" IN ( \''.implode( '\', \'', $inCov ).'\' )' )
						) && $success;
					}
				}
				// Fin TODO: à déplacer dans le modèle ?

				$this->_setFlashResult( 'Save', $success );

				if( $success ) {
					$this->Cov58->commit();
					$this->redirect( array( 'controller'=>'covs58', 'action'=>'view', $cov58_id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}
			
			$dossierscovs = array();
			
			foreach($themes as $theme) {
				$this->paginate = array(
					$theme => array(
						'fields' => array(
							$theme.'.id',
							'Personne.qual',
							'Personne.nom',
							'Personne.prenom',
							'Cov58.datecommission',
							$theme.'.cov58_id',
							$theme.'.datedemande'
						),
						'contain' => array(
							'Cov58'
						),
						'joins' => array(
							array(
								'table'      => 'personnes',
								'alias'      => 'Personne',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( $theme.'.personne_id = Personne.id' )
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
								$theme.'.etapecov = \'traitement\'',
								$theme.'.etapecov = \'finalise\''
							)
						),
						'limit' => 100,
						'order' => array( $theme.'.datedemande ASC' )
					)
				);
				$dossierscovs[$theme] = $this->paginate( $this->Cov58->{$theme} );
			}

			// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
			if( empty( $this->data ) ) {
				foreach( $dossierscovs as $theme => $dossiercov ) {
					foreach( $dossiercov as $key => $dossier ) {
						$dossierscovs[$theme][$key]['chosen'] =  ( ( $dossier[$theme]['cov58_id'] == $cov58_id ) );
					}
				}
			}

			$options = $this->Cov58->enums();
			foreach($themes as $theme) {
				$options = array_merge($options, $this->Cov58->{$theme}->enums());
			}
			/*$options['Dossierep']['seanceep_id'] = $this->Dossierep->Seanceep->find(
				'list',
				array(
					'conditions' => array(
						'Seanceep.finalisee' => null
					)
				)
			);*/
			$this->set( 'cov58_id', $cov58_id );
			$this->set( compact( 'options', 'dossierscovs', 'cov58', 'themes' ) );
		}
		
		/**
		*
		*/
		
		public function decisioncov ( $cov58_id ) {
			$cov58 = $this->Cov58->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id,
					)
				)
			);

			$this->assert( !empty( $cov58 ), 'error404' );

			if( !empty( $this->data ) ) {
// debug( $this->data );
				$this->Cov58->begin();
				$success = $this->Cov58->saveDecisions( $cov58_id, $this->data );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Cov58->rollback();
// 					$this->Cov58->commit();
// 					$this->redirect( array( 'action' => 'view', $cov58_id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}

			$dossiers = $this->Cov58->dossiersParListe( $cov58_id );

			if( empty( $this->data ) ) {
				$this->data = $this->Cov58->Dossiercov58->prepareFormData( $cov58_id, $dossiers );
			}

			$this->set( compact( 'cov58', 'dossiers' ) );
			$this->set( 'cov58_id', $cov58_id);
			$this->_setOptions();
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Cov58->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
		
	}
?>
