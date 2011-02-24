<?php
	class Decisionradiepoleemploiep93 extends AppModel
	{
		public $name = 'Decisionradiepoleemploiep93';

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
			'Radiepoleemploiep93' => array(
				'className' => 'Radiepoleemploiep93',
				'foreignKey' => 'radiepoleemploiep93_id',
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