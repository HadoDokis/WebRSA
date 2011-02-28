<?php
	class Decisionradiepoleemploiep58 extends AppModel
	{
		public $name = 'Decisionradiepoleemploiep58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision' => array( 'domain' => 'decisionnonrespectsanctionep93' )
				)
			)
		);

		public $belongsTo = array(
			'Radiepoleemploiep58' => array(
				'className' => 'Radiepoleemploiep58',
				'foreignKey' => 'radiepoleemploiep58_id',
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