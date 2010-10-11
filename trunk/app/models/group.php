<?php
	class Group extends AppModel
	{
		public $name = 'Group';

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'parent_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'ParentGroup' => array(
				'className' => 'Group',
				'foreignKey' => 'parent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'ChildGroup' => array(
				'className' => 'Group',
				'foreignKey' => 'parent_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'group_id',
				'dependent' => false,
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
