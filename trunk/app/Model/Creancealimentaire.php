<?php
	class Creancealimentaire extends AppModel
	{
		public $name = 'Creancealimentaire';

		protected $_modules = array( 'caf' );

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