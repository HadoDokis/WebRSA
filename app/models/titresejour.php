<?php
	class Titresejour extends AppModel
	{
		public $name = 'Titresejour';

		public $validate = array(
			'personne_id' => array('numeric')
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