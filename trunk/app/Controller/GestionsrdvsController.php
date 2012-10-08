<?php
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