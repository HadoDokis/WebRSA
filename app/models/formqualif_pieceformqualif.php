<?php
	class FormqualifPieceformqualif extends AppModel
	{
		public $name = 'FormqualifPieceformqualif';

		public $validate = array(
			'formqualif_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceformqualif_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
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