<?php
	class AmenaglogtPieceamenaglogt extends AppModel
	{
		public $name = 'AmenaglogtPieceamenaglogt';

		public $validate = array(
			'amenaglogt_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceamenaglogt_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Amenaglogt' => array(
				'className' => 'Amenaglogt',
				'foreignKey' => 'amenaglogt_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceamenaglogt' => array(
				'className' => 'Pieceamenaglogt',
				'foreignKey' => 'pieceamenaglogt_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>