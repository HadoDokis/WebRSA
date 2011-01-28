<?php
	class Motifreorient extends AppModel
	{
		public $name = 'Motifreorient';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);

		public $hasMany = array(
			'Saisineepreorientsr93' => array(
				'className' => 'Saisineepreorientsr93',
				'foreignKey' => 'motifreorient_id',
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
	}
?>