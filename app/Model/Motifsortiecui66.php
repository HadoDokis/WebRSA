<?php	
	/**
	 * Code source de la classe Motifsortiecui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Motifsortiecui66 ...
	 *
	 * @package app.Model
	 */
	class Motifsortiecui66 extends AppModel
	{
		public $name = 'Motifsortiecui66';

		public $actsAs = array(
			'Autovalidate2',
			'Formattable'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				)
			)
		);

	}
?>