<?php	
	/**
	 * Code source de la classe Originepdo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Originepdo ...
	 *
	 * @package app.Model
	 */
	class Originepdo extends AppModel
	{
		public $name = 'Originepdo';

		public $displayField = 'libelle';

		public $actsAs = array(
			'ValidateTranslate',
			'Autovalidate2',
			'Enumerable' => array(
				'fields' => array(
					'originepcg',
					'cerparticulier'
				)
			)
		);

		public $validate = array(
			'libelle' => array(
				array(
						'rule' => 'isUnique',
						'message' => 'Valeur déjà utilisée'
				),
			)
		);

		public $hasMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'originepdo_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => 'originepdo_id',
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