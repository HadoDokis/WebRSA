<?php
	class Nonoriente66 extends AppModel
	{
		public $name = 'Nonoriente66';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'reponseallocataire' => array( 'type' => 'no' )
				)
			)
		);
		
		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>