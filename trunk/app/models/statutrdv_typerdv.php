<?php
	class StatutrdvTyperdv extends AppModel
	{
		public $name = 'StatutrdvTyperdv';

		public $validate = array(
			'statutrdv_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'typerdv_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'nbavantpassageep' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Statutrdv' => array(
				'className' => 'Statutrdv',
				'foreignKey' => 'statutrdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typerdv' => array(
				'className' => 'Typerdv',
				'foreignKey' => 'typerdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>