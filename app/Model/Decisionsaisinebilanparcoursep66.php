<?php
	/**
	 * Code source de la classe Decisionsaisinebilanparcoursep66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractDecisionep', 'Model/Abstractclass' );

	/**
	 * La classe Decisionsaisinebilanparcoursep66 ...
	 *
	 * @package app.Model
	 */
	class Decisionsaisinebilanparcoursep66 extends AbstractDecisionep
	{
		public $name = 'Decisionsaisinebilanparcoursep66';

		public $actsAs = array(
			'Autovalidate2',
			'Dependencies',
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'maintienorientparcours',
					'changementrefparcours',
					'reorientation'
				)
			),
			'Formattable' => array(
				'suffix' => array(
					'typeorient_id',
					'structurereferente_id',
					'referent_id'
				)
			),
			'ValidateTranslate',
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
			'typeorient_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'maintien', 'reorientation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'maintien', 'reorientation' ) ),
					'message' => 'Champ obligatoire',
				),
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Structurereferente', 'Typeorient' ),
					'message' => 'La structure référente ne correspond pas au type d\'orientation',
				),
			),
			'referent_id' => array(
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Referent', 'Structurereferente' ),
					'message' => 'Le référent n\'appartient pas à la structure référente',
				),
			),
			'changementrefparcours' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'maintien', 'reorientation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'typeorientprincipale_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'maintien', 'reorientation' ) ),
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
