<?php
	/**
	 * Code source de la classe SuivisinstructionController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SuivisinstructionController ...
	 *
	 * @package app.Controller
	 */
	class SuivisinstructionController  extends AppController
	{
		public $name = 'Suivisinstruction';

		public $uses = array( 'Suiviinstruction', 'Option', 'Dossier', 'Serviceinstructeur' );

		public $components = array( 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'view' => 'Suivisinstruction:index'
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
			$this->set( 'suiirsa', $this->Option->suiirsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
		}

		/**
		 *
		 * @param integer $dossier_id
		 */
		public function index( $dossier_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			// Recherche des adresses du foyer
			$suivisinstruction = $this->Suiviinstruction->find(
				'all',
				array(
					'conditions' => array( 'Suiviinstruction.dossier_id' => $dossier_id ),
					'recursive' => -1
				)
			);

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'suivisinstruction', $suivisinstruction );
		}

		/**
		 *
		 * @param integer $suiviinstruction_id
		 */
		public function view( $suiviinstruction_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $suiviinstruction_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Suiviinstruction->dossierId( $suiviinstruction_id ) ) ) );

			$suiviinstruction = $this->Suiviinstruction->find(
				'first',
				array(
					'conditions' => array(
						'Suiviinstruction.id' => $suiviinstruction_id
					),
				'recursive' => -1
				)

			);
			$this->assert( !empty( $suiviinstruction ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $suiviinstruction['Suiviinstruction']['dossier_id'] );
			$this->set( 'suiviinstruction', $suiviinstruction );
		}
	}

?>