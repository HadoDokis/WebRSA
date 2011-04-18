<?php
	class Passagecommissionep extends AppModel
	{
		/**
		*
		*/

		public $recursive = -1;

		/**
		*
		*/

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etatdossierep'
				)
			)
		);

		/**
		*
		*/

		public $belongsTo = array(
			'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
		


		public $hasMany = array(
			'Decisionreorientationep93' => array(
				'className' => 'Decisionreorientationep93',
				'foreignKey' => 'passagecommissionep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

	}
?>