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
					// Thèmes 93
					'nonrespectsanctionep93',
					'saisineepreorientsr93',
					'defautinsertionep66'
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
	}
?>
