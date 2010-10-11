<?php
class RegroupementzonegeoZonegeographique extends AppModel {

	var $name = 'RegroupementzonegeoZonegeographique';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
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
