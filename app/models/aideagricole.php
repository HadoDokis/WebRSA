<?php
	class Aideagricole extends AppModel
	{
		public $name = 'Aideagricole';

		public $validate = array(
			'infoagricole_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Infoagricole' => array(
				'className' => 'Infoagricole',
				'foreignKey' => 'infoagricole_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>