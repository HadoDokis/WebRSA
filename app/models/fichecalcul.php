<?php

	class Fichecalcul extends AppModel
	{
		public $name = 'Fichecalcul';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'name'
				)
			)
		);

		public $belongsTo = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'traitementpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
	}
?>
