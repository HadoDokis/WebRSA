<?php
	class Allocationsoutienfamilial extends AppModel
	{
		public $name = 'Allocationsoutienfamilial';

		protected $_modules = array( 'caf' );

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
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