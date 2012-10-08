<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonorientationproep66 extends AppModel
	{
		public $name = 'Decisionnonorientationproep66';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'decisionpcg'
				)
			),
			'Validation.Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id' )
			)
		);

		public $belongsTo = array(
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
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
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
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
			'typeorient_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'reorientation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'reorientation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
		);

		/**
		* Modèles contenus pour l'historique des passages en EP
		*/

		public function containDecision() {
			return array(
				'Typeorient',
				'Structurereferente',
			);
		}
	}
?>
