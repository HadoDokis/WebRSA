<?php
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