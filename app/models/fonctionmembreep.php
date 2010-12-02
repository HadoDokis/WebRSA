<?php
	class Fonctionmembreep extends AppModel
	{
		public $name = 'Fonctionmembreep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);		

		public $hasMany = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'fonctionmembreep_id',
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