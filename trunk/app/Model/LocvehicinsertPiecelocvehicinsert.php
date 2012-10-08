<?php
	class LocvehicinsertPiecelocvehicinsert extends AppModel
	{
		public $name = 'LocvehicinsertPiecelocvehicinsert';

		public $validate = array(
			'locvehicinsert_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'piecelocvehicinsert_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Locvehicinsert' => array(
				'className' => 'Locvehicinsert',
				'foreignKey' => 'locvehicinsert_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Piecelocvehicinsert' => array(
				'className' => 'Piecelocvehicinsert',
				'foreignKey' => 'piecelocvehicinsert_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>