<?php	
	/**
	 * Code source de la classe GestionsrdvsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GestionsrdvsController ...
	 *
	 * @package app.Controller
	 */
	class GestionsrdvsController extends AppController
	{
		public $name = 'Gestionsrdvs';
		public $uses = array( 'Rendezvous' );

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
		}
	}
?>