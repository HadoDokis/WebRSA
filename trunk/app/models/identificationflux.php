<?php
	class Identificationflux extends AppModel
	{
		public $name = 'Identificationflux';

		public $hasMany = array(
			'Totalisationacompte' => array(
				'className' => 'Totalisationacompte',
				'foreignKey' => 'identificationflux_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Transmissionflux' => array(
				'className' => 'Transmissionflux',
				'foreignKey' => 'identificationflux_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>