<?php
	class Compositionregroupementep extends AppModel
	{
		public $name = 'Compositionregroupementep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Fonctionmembreep' => array(
				'className' => 'Fonctionmembreep',
				'foreignKey' => 'fonctionmembreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Regroupementep' => array(
				'className' => 'Regroupementep',
				'foreignKey' => 'regroupementep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>