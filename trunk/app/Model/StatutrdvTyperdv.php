<?php
	class StatutrdvTyperdv extends AppModel
	{
		public $name = 'StatutrdvTyperdv';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'typecommission'
				)
			),
			'ValidateTranslate'
		);

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
			'typecommission' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
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