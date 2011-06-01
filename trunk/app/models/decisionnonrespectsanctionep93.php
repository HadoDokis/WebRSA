<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonrespectsanctionep93 extends AppModel
	{
		public $name = 'Decisionnonrespectsanctionep93';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'decisionpcg'
				)
			),
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
		);

		/**
		*
		*/

		public $validate = array(
			'decision' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			),
		);

		/**
		*
		*/

		public $belongsTo = array(
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'passagecommissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		/**
		* Les règles de validation qui seront utilisées lors de la validation
		* en EP des décisions de la thématique
		*/

		public $validateFinalisation = array(
			'decision' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			),
		);
	}
?>
