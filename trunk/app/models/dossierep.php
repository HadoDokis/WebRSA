<?php
	class Dossierep extends AppModel
	{
		public $name = 'Dossierep';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etapedossierep',
					'themeep',
				)
			)
		);

		public $belongsTo = array(
			'Seanceep' => array(
				'className' => 'Seanceep',
				'foreignKey' => 'seanceep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasOne = array(
			// Thèmes 66
			'Saisineepbilanparcours66' => array(
				'className' => 'Saisineepbilanparcours66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Saisineepdpdo66' => array(
				'className' => 'Saisineepdpdo66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			// Thèmes 93
			'Saisineepreorientsr93' => array(
				'className' => 'Saisineepreorientsr93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			// Thèmes 58
			'Nonorientationpro58' => array(
				'className' => 'Nonorientationpro58',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		/**
		*
		*/

		public function themeTraite( $id ) {
			$ep = $this->find(
				'first',
				array(
					'conditions' => array(
						"{$this->alias}.{$this->primaryKey}" => $id
					),
					'contain' => array(
						'Seanceep' => array(
							'Ep'
						)
					)
				)
			);

			$themes = $this->Seanceep->Ep->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
				if( in_array( $ep['Seanceep']['Ep'][$theme], array( 'ep', 'cg' ) ) ) {
					$themesTraites[$theme] = $ep['Seanceep']['Ep'][$theme];
				}
			}
			return $themesTraites;
		}

		/**
		*
		*/

		public function prepareFormDataUnique( $dossierep_id, $dossier, $niveauDecision ) {
			$data = array();

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->{$model}->prepareFormDataUnique(
						$dossierep_id,
						$dossier,
						$niveauDecision
					)
				);
			}

			return $data;
		}

		/**
		*
		*/

		public function sauvegardeUnique( $dossierep_id, $data, $niveauDecision ) {
			$success = true;

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$success = $this->{$model}->saveDecisionUnique( $data, $niveauDecision ) && $success;
			}

			return $success;
		}

		/**
		* Retourne un array de chaînes de caractères indiquant pourquoi on ne
		* peut pas créer de dossier d'EP pour la personne.
		*
		* Les valeurs possibles sont:
		* 	- Personne.id: la personne n'existe pas en base ou n'a pas de prestation RSA
		* 	- Situationdossierrsa.etatdosrsa: le dossier ne se trouve pas dans un état ouvert
		* 	- Prestation.rolepers: la personne n'est ni demandeur ni conjoint RSA
		* 	- Calculdroitrsa.toppersdrodevorsa: la personne n'est pas soumise à droits et devoirs
		*
		* @param integer $personne_id L'id technique de la personne
		* @return array
		* @access public
		*/

		public function erreursCandidatePassage( $personne_id ) {
			$result = $this->Personne->find(
				'first',
				array(
					'fields' => array(
						'Situationdossierrsa.etatdosrsa',
						'Prestation.rolepers',
						'Calculdroitrsa.toppersdrodevorsa'
					),
					'joins' => array(
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Personne.foyer_id'
							)
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.dossier_id = Situationdossierrsa.dossier_id',
							)
						),
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest' => 'RSA',
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id'
							)
						),
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'contain' => false
				)
			);
			$result = Set::flatten( $result );

			$errors = array();
			if( empty( $result ) ) {
				$errors[] = 'Personne.id';
			}
			else {
				if( !in_array( $result['Situationdossierrsa.etatdosrsa'], ClassRegistry::init( 'Situationdossierrsa' )->etatOuvert() ) ) {
					$errors[] = 'Situationdossierrsa.etatdosrsa';
				}
				if( !in_array( $result['Prestation.rolepers'], array( 'DEM', 'CJT' ) ) ) {
					$errors[] = 'Prestation.rolepers';
				}
				if( empty( $result['Calculdroitrsa.toppersdrodevorsa'] ) ) {
					$errors[] = 'Calculdroitrsa.toppersdrodevorsa';
				}
			}

			return $errors;
		}
	}
?>
