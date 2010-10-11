<?php
	class Traitementpdo extends AppModel
	{
		public $name = 'Traitementpdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'hascourrier',
					'hasrevenu',
					'haspiecejointe',
					'hasficheanalyse'
				)
			)
		);

		public $validate = array(
			'propopdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'descriptionpdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'traitementtypepdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Descriptionpdo' => array(
				'className' => 'Descriptionpdo',
				'foreignKey' => 'descriptionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Traitementtypepdo' => array(
				'className' => 'Traitementtypepdo',
				'foreignKey' => 'traitementtypepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>