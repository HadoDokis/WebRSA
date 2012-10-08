<?php
	class ActprofPieceactprof extends AppModel
	{
		public $name = 'ActprofPieceactprof';

		public $validate = array(
			'actprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceactprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Actprof' => array(
				'className' => 'Actprof',
				'foreignKey' => 'actprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceactprof' => array(
				'className' => 'Pieceactprof',
				'foreignKey' => 'pieceactprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>