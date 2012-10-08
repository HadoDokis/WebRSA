<?php
	class Sitecov58 extends AppModel
	{
		public $name = 'Sitecov58';

		public $order = array( 'Sitecov58.name ASC' );

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);

		public $hasMany = array(
			'Cov58' => array(
				'className' => 'Cov58',
				'foreignKey' => 'sitecov58_id',
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
		
		public $hasAndBelongsToMany = array(
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'sitescovs58_zonesgeographiques',
				'foreignKey' => 'sitecov58_id',
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
				'with' => 'Sitecov58Zonegeographique'
			)
		);
	}
?>