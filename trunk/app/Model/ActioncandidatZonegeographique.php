<?php
	class ActioncandidatZonegeographique extends AppModel
	{
		public $name = 'ActioncandidatZonegeographique';

		public $actsAs = array(
			'Autovalidate2',
			'Formattable',
			'ValidateTranslate'
		);

		public $belongsTo = array(
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'foreignKey' => 'zonegeographique_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
