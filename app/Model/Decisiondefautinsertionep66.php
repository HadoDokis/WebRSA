<?php
	class Decisiondefautinsertionep66 extends AppModel
	{
		public $name = 'Decisiondefautinsertionep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'decisionsup'
				)
			)
		);

		public $hasOne = array(
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => 'dossierpcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
		
		public $belongsTo = array(
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
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
// 			'decisionsup' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'decision', true, array( 'reorientationprofverssoc', 'reorientationsocversprof' ) ),
// 					'message' => 'Champ obligatoire',
// 				),
// 			),
			'typeorient_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'reorientationprofverssoc', 'reorientationsocversprof' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'reorientationprofverssoc', 'reorientationsocversprof' ) ),
					'message' => 'Champ obligatoire',
				),
			),
		);

		/**
		 * Retourne les modèles liés à la décision pour l'historique des passages en EP.
		 *
		 * @return array
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