<?php
	class Decisionsanctionrendezvousep58 extends AppModel
	{
		public $name = 'Decisionsanctionrendezvousep58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
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
		);

		/**
		 * Retourne les modèles contenus pour l'historique des passages en EP.
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