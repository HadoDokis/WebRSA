<?php
	class GestionsepsController extends AppController
	{
		public $name = 'Gestionseps';
		public $uses = array( 'Eps' );


		function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

// 			$compteurs = array(
// 				'Fonctionmembreep' => ClassRegistry::init( 'Fonctionmembreep' )->find( 'count' ),
// 				'Regroupementep' => ClassRegistry::init( 'Regroupementep' )->find( 'count' )
// 			);
// 			$this->set( compact( 'compteurs' ) );

		}

	}

?>