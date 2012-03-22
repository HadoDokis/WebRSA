<?php
	class Propodecisioncer66 extends AppModel
	{
		public $name = 'Propodecisioncer66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'isvalidcer' => array( 'type' => 'no' )
				)
			),
			'Formattable'
		);
		
		public $validate = array(
			'isvalidcer' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);
		
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public $hasAndBelongsToMany = array(
			'Motifcernonvalid66' => array(
				'className' => 'Motifcernonvalid66',
				'joinTable' => 'motifscersnonvalids66_proposdecisioncers66',
				'foreignKey' => 'propodecisioncer66_id',
				'associationForeignKey' => 'motifcernonvalid66_id',
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