<?php	
	/**
	 * Code source de la classe TestsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TestsController ...
	 *
	 * @package app.Controller
	 */
	class TestsController extends AppController
	{
		public $uses = array();

		public $aucunDroit = array( 'index' );

		public function index() {
			$themes = array(
				'Epinay s/ Seine' => 20,
				'Pierrefitte' => 12,
				'Villetaneuse' => 15,
				'Saint Denis' => 10,
				'Ile-St-Denis' => 15,
				'Saint-Ouen' => 8
			);
			$this->set( compact( 'themes' ) );
		}
	}
?>