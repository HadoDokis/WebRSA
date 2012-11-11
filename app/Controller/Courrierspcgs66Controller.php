<?php	
	/**
	 * Code source de la classe Courrierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Courrierspcgs66Controller ...
	 *
	 * @package app.Controller
	 */
	class Courrierspcgs66Controller extends AppController
	{

		public $name = 'Courrierspcgs66'; 
                public $uses = array( 'Typecourrierpcg66' );

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}

			/*$compteurs = array(
				'Courrierpdo' => ClassRegistry::init( 'Courrierpdo' )->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );*/
		}
	}

?>