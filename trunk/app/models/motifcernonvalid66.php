<?php
	class Motifcernonvalid66 extends AppModel
	{
		public $name = 'Motifcernonvalid66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $hasAndBelongsToMany = array(
			'Propodecisioncer66' => array(
				'className' => 'Propodecisioncer66',
				'joinTable' => 'motifscersnonvalids66_proposdecisionscers66',
				'foreignKey' => 'motifcernonvalid66_id',
				'associationForeignKey' => 'propodecisioncer66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Motifcernonvalid66Propodecisioncer66'
			)
		);
	}
?>