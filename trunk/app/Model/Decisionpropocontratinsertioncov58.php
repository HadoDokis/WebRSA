<?php
	class Decisionpropocontratinsertioncov58 extends AppModel
	{
		public $name = 'Decisionpropocontratinsertioncov58';

		public $recursive = -1;

		public $actsAs = array(
			'Validation.Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etapecov',
					'decisioncov'
				)
			)
		);

		public $belongsTo = array(
			'Passagecov58' => array(
				'className' => 'Passagecov58',
				'foreignKey' => 'passagecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		// FIXME: dans ce cas, il faudra permettre au champ decision de prendre la valeur NULL

		/**
		* Les règles de validation qui seront utilisées lors de la validation
		* en COV des décisions de la thématique
		*/

		public $validateFinalisation = array(
			'decisioncov' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			)
		);

	}
?>