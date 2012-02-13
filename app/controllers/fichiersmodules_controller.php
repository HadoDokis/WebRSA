<?php

	class FichiersmodulesController extends AppController{

		public $name = 'Fichiersmodules';
		public $uses = array( 'Fichiermodule' );


		/**
		*   Suppression du fichiers préalablement associés à un traitement donné
		*/

		public function delete( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );

			if( $this->Fichiermodule->delete( $fichiermodule_id ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
	}
?>