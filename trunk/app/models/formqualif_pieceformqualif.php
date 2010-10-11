<?php
	class FormqualifPieceformqualif extends AppModel
	{
		public $name = 'FormqualifPieceformqualif';

		public $validate = array(
			'formqualif_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceformqualif_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Formqualif' => array(
				'className' => 'Formqualif',
				'foreignKey' => 'formqualif_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceformqualif' => array(
				'className' => 'Pieceformqualif',
				'foreignKey' => 'pieceformqualif_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>