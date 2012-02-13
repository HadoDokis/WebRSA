<?php
	class IntegrationfichiersapreController extends AppController
	{
		/**
		*
		*/

		public function index() {
			$this->paginate = array(
				'Integrationfichierapre' => array(
					'fields' => array(
						'id',
						'date_integration',
						'nbr_atraiter',
						'nbr_succes',
						'nbr_erreurs',
						'fichier_in'
					),
					'order' => array( 'Integrationfichierapre.date_integration DESC' )
				)
			);

			$integrationfichiersapre = $this->paginate( 'Integrationfichierapre' );

			$this->set( compact( 'integrationfichiersapre' ) );
		}

		/**
		*
		*/

		public function download( $id = null ) {
			$integrationfichierapre = $this->Integrationfichierapre->findById( $id, null, null, -1 );
			$this->assert( !empty( $integrationfichierapre ), 'invalidParameter' );

			Configure::write( 'debug', 0 );
			header('Content-Type: application/octet-stream; charset='.Configure::read( 'App.encoding' ) );
			header('Content-Disposition: attachment; filename="rejets-'.Set::classicExtract( $integrationfichierapre, 'Integrationfichierapre.fichier_in' ).'"');
			echo Set::classicExtract( $integrationfichierapre, 'Integrationfichierapre.erreurs' );
			die();
		}
	}
?>