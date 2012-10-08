<?php
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