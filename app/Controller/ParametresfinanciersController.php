<?php	
	/**
	 * Code source de la classe ParametresfinanciersController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ParametresfinanciersController ...
	 *
	 * @package app.Controller
	 */
	class ParametresfinanciersController extends AppController
	{
		public $name = 'Parametresfinanciers';

		public $helpers = array( 'Xform', 'Xhtml' );

		/**
		*
		*/

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
			}
			$this->set( 'parametrefinancier',  $this->{$this->modelClass}->find( 'first' ) );
		}

		/**
		*
		*/

		public function edit() {
			$parametrefinancier = $this->{$this->modelClass}->find( 'first' );
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametresfinanciers', 'action' => 'index' ) );
			}
			if( !empty( $this->request->data ) ) {
				$this->{$this->modelClass}->create( $this->request->data );
				if( $this->{$this->modelClass}->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué' ), 'flash/success' );
					$this->redirect( array( 'controller' => 'parametresfinanciers', 'action' => 'index' ) );
				}
			}
			else {
				$this->request->data = $parametrefinancier;
			}
		}
	}
?>