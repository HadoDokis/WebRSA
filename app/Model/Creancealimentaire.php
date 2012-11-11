<?php	
	/**
	 * Code source de la classe Creancealimentaire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Creancealimentaire ...
	 *
	 * @package app.Model
	 */
	class Creancealimentaire extends AppModel
	{
		public $name = 'Creancealimentaire';

		protected $_modules = array( 'caf' );

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>