<?php
	/**
	* Commision d'orientation et validation (COV)
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Cov58 extends AppModel
	{
		public $name = 'Cov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etatcov'
				)
			),
			'Gedooo'
		);

		public $belongsTo = array(
			'Sitecov58' => array(
				'className' => 'Sitecov58',
				'foreignKey' => 'sitecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'cov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		public function search( $criterescov58 ) {
			/// Conditions de base

			$conditions = array();

			if ( isset($criterescov58['Cov58']['name']) && !empty($criterescov58['Cov58']['name']) ) {
				$conditions[] = array( 'Cov58.name ILIKE' => $this->wildcard( $criterescov58['Cov58']['name'] ) );
			}

			if ( isset($criterescov58['Cov58']['sitecov58_id']) && !empty($criterescov58['Cov58']['sitecov58_id']) ) {
				$conditions[] = array( 'Cov58.sitecov58_id' => $criterescov58['Cov58']['sitecov58_id'] );
			}

			if ( isset($criterescov58['Cov58']['lieu']) && !empty($criterescov58['Cov58']['lieu']) ) {
				$conditions[] = array('Cov58.lieu'=>$criterescov58['Cov58']['lieu']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criterescov58['Cov58']['datecommission'] ) && !empty( $criterescov58['Cov58']['datecommission'] ) ) {
				$valid_from = ( valid_int( $criterescov58['Cov58']['datecommission_from']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['day'] ) );
				$valid_to = ( valid_int( $criterescov58['Cov58']['datecommission_to']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Cov58.datecommission BETWEEN \''.implode( '-', array( $criterescov58['Cov58']['datecommission_from']['year'], $criterescov58['Cov58']['datecommission_from']['month'], $criterescov58['Cov58']['datecommission_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescov58['Cov58']['datecommission_to']['year'], $criterescov58['Cov58']['datecommission_to']['month'], $criterescov58['Cov58']['datecommission_to']['day'] + 1 ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'Cov58.id',
					'Cov58.name',
					'Cov58.datecommission',
					'Cov58.etatcov',
					'Cov58.observation'
				),
				'contain'=> array( 'Sitecov58' => array( 'fields' => array( 'name' ) ) ),
				'order' => array( '"Cov58"."datecommission" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		public function dossiersParListe( $cov58_id ) {
			$dossiers = array();

			foreach( $this->Dossiercov58->Themecov58->find('list') as $theme ) {
				$model = Inflector::classify( $theme );
				$fields = array_merge(
					$this->Dossiercov58->{$model}->getFields(),
					array(
						'Dossiercov58.id',
						'Dossiercov58.personne_id',
						'Dossiercov58.themecov58_id',
						'Dossiercov58.etapecov',
						'Dossiercov58.cov58_id'
					)
				);
				$dossiers[$model]['liste'] = $this->Dossiercov58->find(
					'all',
					array(
						'fields' => $fields,
						'conditions' => array(
							'Dossiercov58.cov58_id' => $cov58_id,
							'Dossiercov58.etapecov NOT' => 'finalise'
						),
						'contain' => array(
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
						'joins' => $this->Dossiercov58->{$model}->getJoins()
					)
				);
			}
			return $dossiers;
		}

		public function saveDecisions( $cov58_id, $datas ) {
			$success = true;
			$cov58 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);
			foreach($this->Dossiercov58->Themecov58->find('list') as $theme) {
				$class = Inflector::classify($theme);
				if ( isset( $datas[$class] ) && !empty( $datas[$class] ) ) {
					foreach($datas[$class] as $data) {
						if ( $data['decisioncov'] != 'ajourne' && !empty( $data['decisioncov'] ) ) {
							$success = $this->Dossiercov58->{$class}->saveDecision($data, $cov58) && $success;
						}
						elseif ( !empty( $data['decisioncov'] ) ) {
							$dossiercov58 = $this->Dossiercov58->{$class}->find(
								'first',
								array(
									'conditions' => array(
										$class.'.id' => $data['id']
									),
									'contain' => array(
										'Dossiercov58'
									)
								)
							);
							$dossiercov58['Dossiercov58']['etapecov'] = 'ajourne';
							$success = $this->Dossiercov58->save($dossiercov58['Dossiercov58']) && $success;
						}
					}
				}
			}
			return $success;
		}

		/**
		*
		*/

		public function getPdfOrdreDuJour( $cov58_id ) {
			$cov58_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58_id',
					'Themecov58.id',
					'Themecov58.name',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',
				),
				'joins' => array(
					array(
						'table'      => 'themescovs58',
						'alias'      => 'Themecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Themecov58.id = Dossiercov58.themecov58_id" ),
					),
					array(
						'table'      => 'covs58',
						'alias'      => 'Cov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Cov58.id = Dossiercov58.cov58_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossiercov58.personne_id = Personne.id" ),
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
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'contain' => false,
				'conditions' => array(
					'Cov58.id' => $cov58_id
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Dossiercov58->enums() );

			$dossierscovs58 = $this->Dossiercov58->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			foreach( $dossierscovs58 as $key => $dossiercov58 ) {
				$dossierscovs58[$key]['Themecov58']['name'] = __d( 'dossiercov58', 'ENUM::THEMECOV::'.$dossiercov58['Themecov58']['name'], true );
			}

// debug($dossierscovs58);
// die();
			return $this->ged(
				array_merge(
					array(
						$cov58_data,
						'Dossierscovs58' => $dossierscovs58
					)
				),
				"{$this->alias}/ordedujour.odt",
				true,
				$options
			);
		}


		/**
		*
		*/

		public function getPdfPv( $cov58_id ) {
			$cov58_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58_id',
					'Themecov58.id',
					'Themecov58.name',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',
					
				),
				'joins' => array(
					array(
						'table'      => 'themescovs58',
						'alias'      => 'Themecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Themecov58.id = Dossiercov58.themecov58_id" ),
					),
					array(
						'table'      => 'covs58',
						'alias'      => 'Cov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Cov58.id = Dossiercov58.cov58_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossiercov58.personne_id = Personne.id" ),
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
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'contain' => false
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->Dossiercov58->Themecov58->find('list') as $theme ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Dossiercov58->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Dossiercov58->{$model}->enums() );
				}

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Dossiercov58->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );

			$dossierscovs58 = $this->Dossiercov58->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			foreach( $dossierscovs58 as $key => $dossiercov58 ) {
				$dossierscovs58[$key]['Themecov58']['name'] = __d( 'dossiercov58', 'ENUM::THEMECOV::'.$dossiercov58['Themecov58']['name'], true );
			}

// debug( $cov58_data );
// debug($dossierscovs58);
// die();
			return $this->ged(
				array_merge(
					array(
						$cov58_data,
						'Decisionscovs58' => $dossierscovs58,
					)
				),
				"{$this->alias}/pv.odt",
				true,
				$options
			);
		}

	}
?>
