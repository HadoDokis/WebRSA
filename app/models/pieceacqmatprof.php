<?php
	class Pieceacqmatprof extends AppModel
	{
		public $name = 'Pieceacqmatprof';

		public $displayField = 'libelle';

		public $order = array( 'Pieceacqmatprof.libelle ASC' );

		public $hasAndBelongsToMany = array(
			'Acqmatprof' => array(
				'className' => 'Acqmatprof',
				'joinTable' => 'acqsmatsprofs_piecesacqsmatsprofs',
				'foreignKey' => 'pieceacqmatprof_id',
				'associationForeignKey' => 'acqmatprof_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AcqmatprofPieceacqmatprof'
			)
		);
	}
?>
