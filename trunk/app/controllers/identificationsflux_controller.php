<?php
	class IdentificationsfluxController  extends AppController
	{

		public $name = 'Identificationsflux';
		public $uses = array( 'Identificationflux', 'Option', 'Totalisationacompte' );


		public function index( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'error404' );

			// Recherche des adresses du foyer
			$identflux = $this->Identificationflux->find(
				'all',
				array(
					'conditions' => array( 'Identificationflux.id' => $id ),
					'recursive' => -1
				)
			);

			// Assignations à la vue
			$this->set( 'identflux', $identflux );
		}
	}
?>