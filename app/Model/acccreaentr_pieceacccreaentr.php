<?php
	class AcccreaentrPieceacccreaentr extends AppModel
	{
		public $name = 'AcccreaentrPieceacccreaentr';

		public $validate = array(
			'acccreaentr_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceacccreaentr_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Acccreaentr' => array(
				'className' => 'Acccreaentr',
				'foreignKey' => 'acccreaentr_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceacccreaentr' => array(
				'className' => 'Pieceacccreaentr',
				'foreignKey' => 'pieceacccreaentr_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>