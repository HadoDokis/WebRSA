<?php
	class Refpresta extends AppModel
	{
		public $name = 'Refpresta';

		public $hasMany = array(
			'Prestform' => array(
				'className' => 'Prestform',
				'foreignKey' => 'refpresta_id',
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