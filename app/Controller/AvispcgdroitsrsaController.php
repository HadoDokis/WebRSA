<?php
	/**
	 * Code source de la classe AvispcgdroitsrsaController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AvispcgdroitsrsaController ...
	 *
	 * @package app.Controller
	 */
	class AvispcgdroitsrsaController extends AppController
	{
		public $name = 'Avispcgdroitsrsa';

		public $uses = array( 'Avispcgdroitrsa', 'Option' , 'Dossier', 'Condadmin',  'Reducrsa');

		public $components = array( 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array( 'view' => 'Actionscandidats:index' );

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
			$return = parent::beforeFilter();
			$this->set( 'avisdestpairsa', $this->Option->avisdestpairsa() );
			$this->set( 'typeperstie', $this->Option->typeperstie() );
			$this->set( 'aviscondadmrsa', $this->Option->aviscondadmrsa() );
			return $return;
		}

		/**
		 *
		 * @param integer $dossier_id
		 */
		public function index( $dossier_id = null ){
			$this->assert( valid_int( $dossier_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			$avispcgdroitrsa = $this->Avispcgdroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Avispcgdroitrsa.dossier_id' => $dossier_id
					),
					'recursive' => -1
				)
			) ;

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'avispcgdroitrsa', $avispcgdroitrsa );
		}

		/**
		 *
		 * @param integer $avispcgdroitrsa_id
		 */
		public function view( $avispcgdroitrsa_id = null ) {
			$this->assert( valid_int( $avispcgdroitrsa_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Avispcgdroitrsa->dossierId( $avispcgdroitrsa_id ) ) ) );

			$avispcgdroitrsa = $this->Avispcgdroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Avispcgdroitrsa.id' => $avispcgdroitrsa_id
					),
				'recursive' => -1
				)

			);

			$this->assert( !empty( $avispcgdroitrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $avispcgdroitrsa['Avispcgdroitrsa']['dossier_id'] );
			$this->set( 'avispcgdroitrsa', $avispcgdroitrsa );
		}
	}
?>