<?php

	class Contenutextareacourrierpdo extends AppModel
	{
		public $name = 'Contenutextareacourrierpdo';

		public $actsAs = array(
			'Autovalidate2'
		);

		public $belongsTo = array(
			'CourrierpdoTraitementpdo' => array(
				'className' => 'CourrierpdoTraitementpdo',
				'foreignKey' => 'courrierpdo_traitementpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Textareacourrierpdo' => array(
				'className' => 'Textareacourrierpdo',
				'foreignKey' => 'textareacourrierpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

	}
?>