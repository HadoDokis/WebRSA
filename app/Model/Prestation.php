<?php
	class Prestation extends AppModel
	{
		public $name = 'Prestation';

		protected $_modules = array( 'caf' );

		public $validate = array(
			// Role personne
			'rolepers' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>