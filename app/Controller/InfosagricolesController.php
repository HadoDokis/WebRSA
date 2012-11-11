<?php
	/**
	 * Code source de la classe InfosagricolesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InfosagricolesController ...
	 *
	 * @package app.Controller
	 */
	class InfosagricolesController extends AppController
	{

		public $name = 'Infosagricoles';
		public $uses = array( 'Infoagricole',  'Option' , 'Personne', 'Aideagricole');

		public $commeDroit = array(
			'view' => 'Infosagricoles:index'
		);

		public function beforeFilter() {
			parent::beforeFilter();
				$this->set( 'regfisagri', $this->Option->regfisagri() );
		}

		public function index( $personne_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $personne_id ), 'error404' );

			$infoagricole = $this->Infoagricole->find(
				'first',
				array(
					'conditions' => array(
						'Infoagricole.personne_id' => $personne_id
					),
					'contain' => array(
						'Personne',
						'Aideagricole'
					)
				)
			);

			// Assignations à la vue
			$this->set( 'personne_id', $personne_id );
			$this->set( 'infoagricole', $infoagricole );
		}

		public function view( $infoagricole_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $infoagricole_id ), 'error404' );

			$infoagricole = $this->Infoagricole->find(
				'first',
				array(
					'conditions' => array(
						'Infoagricole.id' => $infoagricole_id
					),
				'recursive' => -1
				)

			);
			$this->assert( !empty( $infoagricole ), 'error404' );

			// Assignations à la vue
			$this->set( 'personne_id', $infoagricole['Infoagricole']['personne_id'] );
			$this->set( 'infoagricole', $infoagricole );
		}
	}

?>