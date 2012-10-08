<?php
	class PermisbPiecepermisb extends AppModel
	{
		public $name = 'PermisbPiecepermisb';

		public $validate = array(
			'permisb_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'piecepermisb_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Permisb' => array(
				'className' => 'Permisb',
				'foreignKey' => 'permisb_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Piecepermisb' => array(
				'className' => 'Piecepermisb',
				'foreignKey' => 'piecepermisb_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>