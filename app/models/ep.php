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
					'saisineepreorientsr93',
					'saisineepbilanparcours66',
					'saisineepdpdo66',
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
			'Membreep' => array(
				'className' => 'Membreep',
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
		);

		/**
		* Retourne la liste des thèmes traités par les EPs
		*/

		public function themes() {
			$enums = $this->enums();
			return array_keys( $enums['Ep'] );
		}
	}
?>
