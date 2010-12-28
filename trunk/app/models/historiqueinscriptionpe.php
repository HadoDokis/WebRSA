<?php
	class Historiqueinscriptionpe extends AppModel
	{
		public $name = 'Historiqueinscriptionpe';

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