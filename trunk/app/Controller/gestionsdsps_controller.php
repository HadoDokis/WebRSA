<?php
	class GestionsdspsController extends AppController
	{
		public $name = 'Gestionsdsps';
		public $uses = array();

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
		}
	}
?>