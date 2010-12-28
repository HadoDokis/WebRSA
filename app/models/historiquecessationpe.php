<?php
	class Historiquecessationpe extends AppModel
	{
		public $name = 'Historiquecessationpe';

		// FIXME: validation

		public $belongsTo = array(
			'Informationpe' => array(
				'className' => 'Informationpe',
				'foreignKey' => 'informationpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>