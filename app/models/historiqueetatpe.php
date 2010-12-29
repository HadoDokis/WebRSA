<?php
	class Historiqueetatpe extends AppModel
	{
		public $name = 'Historiqueetatpe';

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