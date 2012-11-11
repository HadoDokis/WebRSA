<?php	
	/**
	 * Code source de la classe Contactpartenaire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Contactpartenaire ...
	 *
	 * @package app.Model
	 */
	class Contactpartenaire extends AppModel
	{
		public $name = 'Contactpartenaire';

		public $displayField = 'nom_candidat';

		public $actsAs = array(
			'ValidateTranslate',
		);

		public $validate = array(
			'qual' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'nom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'prenom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'partenaire_id' => array(
				array(
					'rule' => array('notEmpty'),
				),
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'contactpartenaire_id',
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

		public $virtualFields = array(
			'nom_candidat' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);
	}
?>