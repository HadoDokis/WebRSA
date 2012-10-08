<?php
	class RegroupementzonegeoZonegeographique extends AppModel {

		public $name = 'RegroupementzonegeoZonegeographique';

		//The Associations below have been created with all possible keys, those that are not needed can be removed
		public $belongsTo = array(
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'foreignKey' => 'zonegeographique_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Regroupementzonegeo' => array(
				'className' => 'Regroupementzonegeo',
				'foreignKey' => 'regroupementzonegeo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

	}
?>
