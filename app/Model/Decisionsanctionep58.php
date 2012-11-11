<?php	
	/**
	 * Code source de la classe Decisionsanctionep58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisionsanctionep58 ...
	 *
	 * @package app.Model
	 */
	class Decisionsanctionep58 extends AppModel
	{
		public $name = 'Decisionsanctionep58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision',
					'decision2',
					'regularisation',
					'arretsanction'
				)
			),
			'Formattable',
			'Suivisanctionep58'
		);

		public $belongsTo = array(
			'Autrelistesanctionep58' => array(
				'className' => 'Listesanctionep58',
				'foreignKey' => 'autrelistesanctionep58_id',
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
			'listesanctionep58_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision', true, array( 'sanction' ) ),
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
				'Listesanctionep58',
				'Autrelistesanctionep58'
			);
		}
	}
?>