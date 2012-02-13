<?php
	class UserZonegeographique extends AppModel
	{
		public $name = 'UserZonegeographique';

		public $validate = array(
			'user_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'zonegeographique_id' => array(
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
			),
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'foreignKey' => 'zonegeographique_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>