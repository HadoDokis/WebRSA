<?php	
	/**
	 * Code source de la classe Departement.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Departement ...
	 *
	 * @package app.Model
	 */
	class Departement extends AppModel
	{
		public $name = 'Departement';

		public $validate = array(
			'numdep' => array(
				'notempty' => array(
					'rule' => array('notempty')
				),
			),
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty')
				),
			),
		);
	}
?>