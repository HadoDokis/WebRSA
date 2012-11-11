<?php	
	/**
	 * Code source de la classe GestionsepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GestionsepsController ...
	 *
	 * @package app.Controller
	 */
	class GestionsepsController extends AppController
	{
		public $name = 'Gestionseps';
		public $uses = array( 'Eps' );

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
		}
	}
?>