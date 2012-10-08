<?php
	class EpZonegeographique extends AppModel
	{
		public $name = 'EpZonegeographique';

		public $actsAs = array(
			'Validation.Autovalidate',
			'Formattable',
			'ValidateTranslate'
		);

		public $belongsTo = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
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
