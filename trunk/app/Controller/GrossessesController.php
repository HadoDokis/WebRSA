<?php
	/**
	 * Code source de la classe GrossessesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GrossessesController ...
	 *
	 * @package app.Controller
	 */
	class GrossessesController extends AppController
	{
		public $name = 'Grossesses';

		public $uses = array( 'Grossesse',  'Option' , 'Personne' );

		public $components = array( 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'view' => 'Grossesses:index'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'view' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'topressevaeti', $this->Option->topressevaeti() );
			$this->set( 'natfingro', $this->Option->natfingro() );
		}

		/**
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ){
			$this->assert( valid_int( $personne_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$grossesse = $this->Grossesse->find(
				'first',
				array(
					'conditions' => array(
						'Grossesse.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			) ;

			// Assignations à la vue
			$this->set( 'personne_id', $personne_id );
			$this->set( 'grossesse', $grossesse );
		}

		/**
		 *
		 * @param integer $grossesse_id
		 */
		public function view( $grossesse_id = null ) {
			$this->assert( valid_int( $grossesse_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Grossesse->personneId( $grossesse_id ) ) ) );

			$grossesse = $this->Grossesse->find(
				'first',
				array(
					'conditions' => array(
						'Grossesse.id' => $grossesse_id
					),
				'recursive' => -1
				)
			);

			$this->assert( !empty( $grossesse ), 'error404' );

			// Assignations à la vue
			$this->set( 'personne_id', $grossesse['Grossesse']['personne_id'] );
			$this->set( 'grossesse', $grossesse );
		}
	}
?>