<?php
	class Transmissionflux extends AppModel
	{
		public $name = 'Transmissionflux';

		public $validate = array(
			'identificationflux_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Identificationflux' => array(
				'className' => 'Identificationflux',
				'foreignKey' => 'identificationflux_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>