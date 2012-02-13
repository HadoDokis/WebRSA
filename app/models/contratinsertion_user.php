<?php
	class ContratinsertionUser extends AppModel
	{
		public $name = 'ContratinsertionUser';

		public $validate = array(
			'user_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'contratinsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
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
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>