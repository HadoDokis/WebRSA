<?php
	class Dossierscovs58Controller extends AppController
	{
		public $name = 'Dossierscovs58';
		public $helpers = array( 'Default', 'Default2' );
		public $components = array( 'Jetonsfonctions' );



		/**
		*
		*/

		protected function _setOptions() {
			$themescovs58 = $this->Dossiercov58->Themecov58->find('list');

			$options = $this->Dossiercov58->Passagecov58->Cov58->enums();

			$this->set(compact('options'));
		}

		/**
		*
		*/

		public function choose( $cov58_id ) {
			
			$this->Dossiercov58->begin();
			if( $this->Jetonsfonctions->get( $this->name, __FUNCTION__ ) ) {

				$cov58 = $this->Dossiercov58->Passagecov58->Cov58->find(
					'first',
					array(
						'conditions' => array(
							'Cov58.id' => $cov58_id
						),
						'contain' => array(
							'Sitecov58' => array(
								'Zonegeographique'
							)
						)
					)
				);
				

				$conditionsAdresses = array( 'OR' => array() );
				// Début conditions zones géographiques CG 58 et CG 93
				if( Configure::read( 'CG.cantons' ) == false ) {
					$zonesgeographiques = Set::extract(
						$cov58,
						'Sitecov58.Zonegeographique.{n}.codeinsee'
					);
					if( !empty( $zonesgeographiques ) ) {
						foreach( $zonesgeographiques as $zonegeographique ) {
								$conditionsAdresses['OR'][] = "Adresse.numcomptt ILIKE '%".Sanitize::paranoid( $zonegeographique )."%'";
						}
					}
				}
				// Fin conditions zones géographiques
				
				if( in_array( $cov58['Cov58']['etatcov'], array( 'traite', 'finalise' ) ) ) {
					$this->Session->setFlash( 'Impossible d\'attribuer des dossiers à une COV lorsque celle-ci comporte déjà des avis ou des décisions.', 'default', array( 'class' => 'error' ) );
					$this->Dossiercov58->rollback();
					$this->redirect( $this->referer() );
				}

				if( !empty( $this->data ) ) {

					$ajouts = array();
					$suppressions = array();
					$dossiersIds = array();
					foreach( $this->data['Dossiercov58'] as $key => $dossiercov58 ) {
						if( empty( $this->data['Passagecov58'][$key]['chosen'] ) && !empty( $this->data['Passagecov58'][$key]['id'] ) ) {
							$suppressions[] = $this->data['Passagecov58'][$key]['id'];
						}
						else if( !empty( $this->data['Passagecov58'][$key]['chosen'] ) && empty( $this->data['Passagecov58'][$key]['id'] ) ) {
							$ajouts[] = array(
								'cov58_id' => $cov58_id,
								'dossiercov58_id' => $this->data['Dossiercov58'][$key]['id'],
								'user_id' => $this->Session->read( 'Auth.User.id' )
							);
						}
					}

					$success = true;

					if( !empty( $ajouts ) ) {
						$success = $this->Dossiercov58->Passagecov58->saveAll( $ajouts, array( 'atomic' => false ) ) && $success;
					}

					if( !empty( $suppressions ) ) {
						$success = $this->Dossiercov58->Passagecov58->delete( $suppressions ) && $success;
					}

					// Changer l'état de la séance
					$success = $this->Dossiercov58->Passagecov58->Cov58->changeEtatCreeAssocie( $cov58_id ) && $success;

					$this->_setFlashResult( 'Save', $success );

					if( $success ) {
						$this->Jetonsfonctions->release( $this->name, __FUNCTION__ );
						
						$dossiersIds = Set::extract( $this->data, '/Foyer/dossier_id' );//FIXME: retour
						if( !empty( $dossiersIds ) ) {
							$this->Jetons->releaseList( $dossiersIds );
						}

						$this->Dossiercov58->commit();
						$this->redirect( array( 'controller'=>'covs58', 'action'=>'view', $cov58_id, '#dossiers,'.$this->data['Choose']['theme'] ) );
					}
					else {
						$this->Dossiercov58->rollback();
					}
				}

				$themes = $this->Dossiercov58->Passagecov58->Cov58->themesTraites( $cov58_id );

				$listeThemes = null;
				if( !empty( $themes ) ) {
					$listeThemes['OR'] = array();
					foreach($themes as $theme => $niveauDecision) {
						$listeThemes['OR'][] = array( 'Dossiercov58.themecov58' => Inflector::tableize( $theme ) );
					}
					$this->set( 'themeEmpty', false );

					if( empty( $conditionsAdresses['OR'] ) ) {
						$conditionsAdresses = array();
					}

					$queryData = array(
						'fields' => array(
							'Dossiercov58.id',
							'Personne.id',
							'Personne.qual',
							'Personne.nom',
							'Personne.prenom',
							'Foyer.dossier_id',
							'Foyer.enerreur',
							'Cov58.datecommission',
							'Passagecov58.id',
							'Passagecov58.cov58_id',
							'Passagecov58.dossiercov58_id',
							'Dossiercov58.created',
							'Dossiercov58.themecov58',
						),
						'joins' => array(
							array(
								'table'      => 'covs58',
								'alias'      => 'Cov58',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Cov58.id = Passagecov58.cov58_id'
								)
							),
							array(
								'table'      => 'calculsdroitsrsa',
								'alias'      => 'Calculdroitrsa',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Personne.id = Calculdroitrsa.personne_id',
									'Calculdroitrsa.toppersdrodevorsa' => 1
								)
							),
							array(
								'table'      => 'situationsdossiersrsa',
								'alias'      => 'Situationdossierrsa',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Situationdossierrsa.dossier_id = Dossier.id',
									'Situationdossierrsa.etatdosrsa' => $this->Dossiercov58->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert()
								)
							),
						),
						'conditions' => array(
							$conditionsAdresses,
							$listeThemes,
							'Dossiercov58.id NOT IN ('.
								$this->Dossiercov58->Passagecov58->sq(
									array(
										'fields' => array( 'passagescovs58.dossiercov58_id' ),
										'alias' => 'passagescovs58',
										'joins' => array(
											array(
												'table'      => 'covs58',
												'alias'      => 'covs58',
												'type'       => 'INNER',
												'foreignKey' => false,
												'conditions' => array( 'covs58.id = passagescovs58.cov58_id' ),
											),
										),
										'conditions' => array(
											'covs58.id <> ' => $cov58_id,
											'passagescovs58.etatdossiercov <>' => 'reporte'
										)
									)
								)
							.' )',
							'Foyer.dossier_id NOT IN ( '.$this->Jetons->sqIds().' )'
						),
						'limit' => 50,
						'order' => array( 'Dossiercov58.created ASC' )
					);

	// 				$options = $this->Dossiercov58->enums();
					$options['Dossiercov58']['cov58_id'] = $this->Dossiercov58->Passagecov58->Cov58->find(
						'list',
						array(
							'conditions' => array(
								'Cov58.etatcov' => array( 'cree', 'associe', 'valide', 'decision' )
							)
						)
					);
				}
				else {
					$this->set( 'themeEmpty', true );
				}

				$themesChoose = array_keys( $this->Dossiercov58->Passagecov58->Cov58->themesTraites( $cov58_id ) );

				$dossiers = array();
				$countDossiers = 0;
				$originalPaginate = $this->paginate;
				foreach( $themesChoose as $theme ) {
					$class = Inflector::classify( $theme );

					$qdListeDossier = $this->Dossiercov58->{$class}->qdListeDossier( $cov58_id );

					if ( isset( $qdListeDossier['fields'] ) ) {
						$qd['fields'] = array_merge( $qdListeDossier['fields'], $queryData['fields'] );
					}
					$qd['conditions'] = array_merge( array( 'Dossiercov58.themecov58' => Inflector::tableize( $class ) ), $queryData['conditions'] );
					$qd['joins'] = array_merge( $qdListeDossier['joins'], $queryData['joins'] );
					$qd['contain'] = false;
					$qd['limit'] = $queryData['limit'];
					$qd['order'] = $queryData['order'];

					$this->Dossiercov58->{$class}->forceVirtualFields = true;
					$this->paginate = $qd;
					$dossiers[$theme] = $this->paginate( $this->Dossiercov58->{$class} );

					// INFO: pour avoir le formulaire pré-rempli ... à mettre dans le modèle également ?
					if( empty( $this->data ) ) {
						foreach( $dossiers[$theme] as $key => $dossiercov58 ) {
							$dossiers[$theme][$key]['Passagecov58']['chosen'] = ( ( $dossiercov58['Passagecov58']['cov58_id'] == $cov58_id ) );
						}
					}
	// debug($dossiers);
					$countDossiers += count($dossiers[$theme]);
				}
				
				// Obtenir un lock sur les dossiers traités - FIXME: ajouter un champ caché dans le formulaire
				$dossiersIds = array();
				$tmpDossiersIdsParThematique = Set::extract( '{s}.{n}.Foyer.dossier_id', $dossiers );
				foreach( $tmpDossiersIdsParThematique as $tmpDossiersIds ) {
					$dossiersIds = Set::merge( $dossiersIds, $tmpDossiersIds );
				}
				$this->Jetons->getList( array_unique( $dossiersIds ) );
				
				// Champs cachés à passer dans chaque onglet
				$tmp = array();
				foreach( $dossiersIds as $i => $dossierId ) {
					$tmp["Foyer.{$i}.dossier_id"] = array( 'value' => $dossierId );
				}
				$this->set( 'dossiersIds', $tmp );

				$this->paginate = $originalPaginate;
				$this->set( compact( 'dossiers', 'themesChoose' ) );
				$this->set( compact( 'countDossiers' ) );


				$this->set( compact( 'options', 'cov58' ) );
				$this->_setOptions();
				$this->set( 'cov58_id', $cov58_id);
				$this->Dossiercov58->commit();
			}
			else{
				$this->Dossiercov58->rollback();
			}
		}

	}
?>
