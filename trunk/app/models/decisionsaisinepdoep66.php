<?php
	class Decisionsaisinepdoep66 extends AppModel
	{
		public $name = 'Decisionsaisinepdoep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision'
				)
			),
			'Formattable'
		);

		public $belongsTo = array(
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'passagecommissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

	}
?>
