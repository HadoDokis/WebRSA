<?php	
	/**
	 * Code source de la classe PdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PdosController ...
	 *
	 * @package app.Controller
	 */
	class PdosController extends AppController
	{

		public $name = 'Pdos';
		public $uses = array( 'Dossier',  'Propopdo' );

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$compteurs = array(
				'Courrierpdo' => ClassRegistry::init( 'Courrierpdo' )->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );
		}
	}

?>