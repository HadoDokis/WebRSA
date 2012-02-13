<?php
	class Jetonfonction extends AppModel
	{
		public $name = 'Jetonfonction';

		public $validate = array(
			'controller' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'action' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'user_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>