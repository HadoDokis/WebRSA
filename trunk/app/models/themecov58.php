<?php

	class Themecov58 extends AppModel
	{
		public $name = 'Themecov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate'
		);

		public $hasMany = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'themecov58_id',
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
		
	}
?>
