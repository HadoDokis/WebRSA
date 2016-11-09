<?php
	/**
	 * Code source de la classe Suiviaideapre.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Suiviaideapre ...
	 *
	 * @package app.Model
	 */
	class Suiviaideapre extends AppModel
	{
		public $name = 'Suiviaideapre';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $displayField = 'nom_complet';

		public $order = array( 'nom ASC', 'prenom ASC' );

		public $actsAs = array(
			'Formattable' => array(
				'phone' => array( 'numtel' )
			),
			'SoftDeletable' => array( 'find' => false ),
			'ValidateTranslate',
			'Validation.ExtraValidationRules',
		);

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
		);

		public $validate = array(
			'numtel' => array(
				'phoneFr' => array(
					'rule' => 'phoneFr',
					'allowEmpty' => true,
				),
			),
			'qual' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nom' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'prenom' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		public $hasMany = array(
			'Suiviaideapretypeaide' => array(
				'className' => 'Suiviaideapretypeaide',
				'foreignKey' => 'suiviaideapre_id',
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
	}
?>