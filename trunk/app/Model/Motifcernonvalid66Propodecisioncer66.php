<?php
	class Motifcernonvalid66Propodecisioncer66 extends AppModel
	{
		public $name = 'Motifcernonvalid66Propodecisioncer66';

		public $recursive = -1;

		
		public $belongsTo = array(
			'Motifcernonvalid66' => array(
				'className' => 'Motifcernonvalid66',
				'foreignKey' => 'motifcernonvalid66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Propodecisioncer66' => array(
				'className' => 'Propodecisioncer66',
				'foreignKey' => 'propodecisioncer66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>