<?php
	class Aro extends AppModel
	{
		public $name = 'Aro';

		public $belongsTo = array(
			'ParentAro' => array(
				'className' => 'Aro',
				'foreignKey' => 'parent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'ChildAro' => array(
				'className' => 'Aro',
				'foreignKey' => 'parent_id',
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

		public $hasAndBelongsToMany = array(
			'Aco' => array(
				'className' => 'Aco',
				'joinTable' => 'aros_acos',
				'foreignKey' => 'aro_id',
				'associationForeignKey' => 'aco_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AroAco'
			)
		);
	}
?>