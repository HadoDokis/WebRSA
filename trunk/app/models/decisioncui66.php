<?php
	class Decisioncui66 extends AppModel
	{
		public $name = 'Decisioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'decisioncui'
				)
			),
			'Formattable'
		);
		
		public $validate = array(
// 			'propositioncui' => array(
// 				'rule' => 'notEmpty',
// 				'message' => 'Champ obligatoire'
// 			),
// 			'datepropositioncui' => array(
// 				'rule' => 'notEmpty',
// 				'message' => 'Champ obligatoire'
// 			),
// 			'propositioncuielu' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'isaviselu', true, array( '1' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
// 			'datepropositioncuielu' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'isaviselu', true, array( '1' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
// 			'propositioncuireferent' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'isavisreferent', true, array( '1' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
// 			'datepropositioncuireferent' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'isavisreferent', true, array( '1' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			)
		);
		
		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>