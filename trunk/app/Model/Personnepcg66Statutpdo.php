<?php
	class Personnepcg66Statutpdo extends AppModel
	{
		public $name = 'Personnepcg66Statutpdo';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'personnepcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutpdo' => array(
				'className' => 'Statutpdo',
				'foreignKey' => 'statutpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

	}
?>
