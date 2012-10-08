<?php
	class Piecepdo extends AppModel
	{
		public $name = 'Piecepdo';

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>