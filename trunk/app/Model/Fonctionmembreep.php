<?php
	class Fonctionmembreep extends AppModel
	{
		public $name = 'Fonctionmembreep';

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable'
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
			),
			'Compositionregroupementep' => array(
				'className' => 'Compositionregroupementep',
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

		public $validate = array(
			'name' => array(
				array(
					'rule' => array( 'isUnique' )
				)
			)
		);
	}
?>