<?php
	class Decisionreorientationep93 extends AppModel
	{
		public $name = 'Decisionreorientationep93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'decisionpcg'
				)
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
// 			'Reorientationep93' => array(
// 				'className' => 'Reorientationep93',
// 				'foreignKey' => 'reorientationep93_id',
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => ''
// 			),
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
		);

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		// FIXME: dans ce cas, il faudra permettre au champ decision de prendre la valeur NULL

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
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'accepte' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'accepte' ) ),
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
				'Referent',
			);
		}
	}
?>