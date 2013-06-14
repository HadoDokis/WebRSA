<?php	
	/**
	 * Code source de la classe IdentificationsfluxController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe IdentificationsfluxController ...
	 *
	 * @package app.Controller
	 */
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