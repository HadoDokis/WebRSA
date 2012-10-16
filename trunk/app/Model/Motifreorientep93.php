<?php
	class Motifreorientep93 extends AppModel
	{
		public $name = 'Motifreorientep93';

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable'
		);

		public $hasMany = array(
			'Reorientationep93' => array(
				'className' => 'Reorientationep93',
				'foreignKey' => 'motifreorientep93_id',
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