<?php
	class Aco extends AppModel
	{
		public $name = 'Aco';

		public $validate = array(
			'parent_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'ParentAco' => array(
				'className' => 'Aco',
				'foreignKey' => 'parent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'ChildAco' => array(
				'className' => 'Aco',
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
			'Aro' => array(
				'className' => 'Aro',
				'joinTable' => 'aros_acos',
				'foreignKey' => 'aco_id',
				'associationForeignKey' => 'aro_id',
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
