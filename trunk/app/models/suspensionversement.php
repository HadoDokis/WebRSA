<?php
	class Suspensionversement extends AppModel
	{
		public $name = 'Suspensionversement';

		public $validate = array(
			'situationdossierrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'situationdossierrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>