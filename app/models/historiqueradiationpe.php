<?php
	class Historiqueradiationpe extends AppModel
	{
		public $name = 'Historiqueradiationpe';

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