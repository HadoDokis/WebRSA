<?php
	class Ep extends AppModel
	{
		public $name = 'Ep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $hasMany = array(
			'Partep',
			'Seanceep'
		);

		public $hasAndBelongsToMany = array(
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'eps_zonesgeographiques',
				'foreignKey' => 'ep_id',
				'associationForeignKey' => 'zonegeographique_id',
				'unique' => true,
			)
		);
	}
?>