<?php
	class AcqmatprofPieceacqmatprof extends AppModel
	{
		public $name = 'AcqmatprofPieceacqmatprof';

		public $validate = array(
			'acqmatprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceacqmatprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Acqmatprof' => array(
				'className' => 'Acqmatprof',
				'foreignKey' => 'acqmatprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceacqmatprof' => array(
				'className' => 'Pieceacqmatprof',
				'foreignKey' => 'pieceacqmatprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>