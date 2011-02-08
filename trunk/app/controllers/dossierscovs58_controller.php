<?php
	class Dossierscovs58Controller extends AppController
	{
		public $name = 'Dossierscovs58';
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
			return parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$themescovs58 = $this->Dossierscovs58->Cov58->Dossiercov58->Themecov58->find('list');
			
			$options = $this->Cov58->enums();
			
			$this->set(compact('options'));
		}

		/**
		*
		*/

		public function choose( $cov58_id ) {
			$cov58 = $this->Dossiercov58->Cov58->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id,
						'Cov58.etatcov' => 'cree'
					),
					'contain' => false
				)
			);
			
			$themes = $this->Dossiercov58->Cov58->Dossiercov58->Themecov58->find('list');

			if( !empty( $this->data ) ) {
				// Début TODO: à déplacer dans le modèle ?
				$this->Dossiercov58->begin();
				
				foreach($themes as $theme) {
					$class = Inflector::classify( $theme );
					$data = Set::extract( $this->data, '/'.$class );

					$inCov = array();
					$notInCov = array();
					foreach( $data as $dossier ) {
						if( !empty( $dossier[$class]['chosen'] ) ) {
							$inCov[] = $dossier[$class]['id'];
						}
						else {
							$notInCov[] = $dossier[$class]['id'];
						}
					}
					
					$success = true;
					if( !empty( $notInCov ) ) {
						$success = $this->Dossiercov58->updateAll(
							array(
								'Dossiercov58.cov58_id' => null,
								'Dossiercov58.etapecov' => '\'cree\''
							),
							array( '"Dossiercov58"."id" IN ( \''.implode( '\', \'', $notInCov ).'\' )' )
						) && $success;
					}

					if( !empty( $inCov ) ) {
						$success = $this->Dossiercov58->updateAll(
							array(
								'Dossiercov58.cov58_id' => $cov58_id,
								'Dossiercov58.etapecov' => '\'traitement\''
							),
							array( '"Dossiercov58"."id" IN ( \''.implode( '\', \'', $inCov ).'\' )' )
						) && $success;
					}
				}
				// Fin TODO: à déplacer dans le modèle ?

				$this->_setFlashResult( 'Save', $success );

				if( $success ) {
					$this->Dossiercov58->commit();
					$this->redirect( array( 'controller'=>'covs58', 'action'=>'view', $cov58_id ) );
				}
				else {
					$this->Dossiercov58->rollback();
				}
			}
			
			$dossierscovs = array();
			
			foreach($themes as $theme) {
				$class = Inflector::classify($theme);
				$this->paginate = array(
					'fields' => array(
						'Dossiercov58.id',
						'Dossiercov58.cov58_id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Cov58.datecommission',
						$class.'.datedemande'
					),
					'contain' => array(
						'Cov58'
					),
					'joins' => array(
						array(
							'table'      => $theme,
							'alias'      => $class,
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossiercov58.id = '.$class.'.dossiercov58_id' )
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossiercov58.personne_id = Personne.id' )
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
						'Dossiercov58.etapecov NOT' => array( 'traitement', 'finalise' )
					),
					'limit' => 100,
					'order' => array( $class.'.datedemande ASC' )
				);
				$dossierscovs[$class] = $this->paginate( $this->Dossiercov58 );
			}
			
			// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
			if( empty( $this->data ) ) {
				foreach( $dossierscovs as $theme => $dossiercov ) {
					foreach( $dossiercov as $key => $dossier ) {
						$dossierscovs[$theme][$key]['chosen'] =  ( ( $dossier['Dossiercov58']['cov58_id'] == $cov58_id ) );
					}
				}
			}

			$options = $this->Dossiercov58->Cov58->enums();
			$options = array_merge($options, $this->Dossiercov58->enums());
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
		
	}
?>
