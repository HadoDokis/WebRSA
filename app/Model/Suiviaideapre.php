<?php	
	/**
	 * Code source de la classe Suiviaideapre.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suiviaideapre ...
	 *
	 * @package app.Model
	 */
	class Suiviaideapre extends AppModel
	{
		public $name = 'Suiviaideapre';

		public $displayField = 'nom_complet';

		public $order = array( 'nom ASC', 'prenom ASC' );

		public $actsAs = array(
			'SoftDeletable' => array( 'find' => false )
		);

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
		);

		public $validate = array(
			'numtel' => array(
				array(
					'rule' => 'phoneFr',
					'allowEmpty' => true,
					'message' => 'Veuillez entrer un n° de téléphone français'
				),
			),
			'qual' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nom' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'prenom' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
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