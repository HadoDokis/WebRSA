<?php
	class Decisionsanctionep58 extends AppModel
	{
		public $name = 'Decisionsanctionep58';

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
			'Sanctionep58' => array(
				'className' => 'Sanctionep58',
				'foreignKey' => 'sanctionep58_id',
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
			),
			'Listesanctionep58' => array(
				'className' => 'Listesanctionep58',
				'foreignKey' => 'listesanctionep58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		// FIXME: dans ce cas, il faudra permettre au champ decision de prendre la valeur NULL
	}
?>