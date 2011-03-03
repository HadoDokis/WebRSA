<?php
	class Ep extends AppModel
	{
		public $name = 'Ep';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					// Thèmes 66
					'saisineepbilanparcours66',
					'saisineepdpdo66',
					'defautinsertionep66',
					// Thèmes 93
					'nonrespectsanctionep93',
					'saisineepreorientsr93',
					'radiepoleemploiep93',
					// Thèmes 58
					'nonorientationpro58',
					'regressionorientationep58',
					'radiepoleemploiep58'
				)
			)
		);

		public $belongsTo = array(
			'Regroupementep' => array(
				'className' => 'Regroupementep',
				'foreignKey' => 'regroupementep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Seanceep' => array(
				'className' => 'Seanceep',
				'foreignKey' => 'ep_id',
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

		public $hasAndBelongsToMany = array(
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'eps_zonesgeographiques',
				'foreignKey' => 'ep_id',
				'associationForeignKey' => 'zonegeographique_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'EpZonegeographique' // TODO
			),
			'Membreep' => array(
				'className' => 'Membreep',
				'joinTable' => 'eps_membreseps',
				'foreignKey' => 'ep_id',
				'associationForeignKey' => 'membreep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'EpMembreep' // TODO
			),
		);

		// INFO: le behavior Autovalidate ne trouve pas les contraintes UNIQUE (17/02/2011)
		public $validate = array(
			'name' => array(
				array(
					'rule' => array( 'isUnique' ),
				)
			)
		);

		public function listOptions() {
			$results = $this->find(
				'list',
				array(
					'fields' => array(
						'Ep.id',
						'Ep.name'
					),
					'contain' => array(
						'Regroupementep'=>array(
							'fields'=>array(
								'name'
							),
							'order'=>array(
								'Regroupementep.name ASC'
							)
						)
					),
					'order' => array(
						'Ep.name'
					)
				)
			);

			return $results;
		}

		/**
		* Retourne la liste des thèmes traités par les EPs
		*/

		public function themes() {
			$enums = $this->enums();
			return array_keys( $enums['Ep'] );
		}

		/**
		* Retourne une chaîne de 12 caractères formattée comme suit:
		* EP, année sur 4 chiffres, mois sur 2 chiffres, nombre de commissions.
		*/

		public function identifiant() {
			return /*'EP'.date( 'Ym' ).sprintf( "%010s",  */($this->find( 'count' ) + 1 );
		}

		/**
		* Ajout de l'identifiant de la séance lors de la sauvegarde.
		*/

		public function beforeValidate( $options = array() ) {
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$identifiant = Set::classicExtract( $this->data, "{$this->alias}.identifiant" );

			if( empty( $primaryKey ) && empty( $identifiant ) ) {
				$this->data[$this->alias]['identifiant'] = $this->identifiant();
			}

			return true;
		}
	}
?>
