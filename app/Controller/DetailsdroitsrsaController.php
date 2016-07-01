<?php
	/**
	 * Code source de la classe DetailsdroitsrsaController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DetailsdroitsrsaController ...
	 *
	 * @package app.Controller
	 */
	class DetailsdroitsrsaController extends AppController
	{
		public $name = 'Detailsdroitsrsa';

		public $components = array( 'DossiersMenus', 'Jetons2' );

		public $uses = array( 'Detaildroitrsa',  'Option' , 'Dossier', 'Detailcalculdroitrsa');

		public $commeDroit = array(
			'view' => 'Detailsdroitsrsa:index'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'topsansdomfixe', $this->Option->topsansdomfixe() );
			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'topfoydrodevorsa', $this->Option->topfoydrodevorsa() );
			$this->set( 'natpf', ClassRegistry::init('Detailcalculdroitrsa')->enum('natpf') );
			$this->set( 'sousnatpf', $this->Option->sousnatpf() );
		}

		/**
		 *
		 * @param integer $dossier_id
		 */
		public function index( $dossier_id = null ){
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			$detaildroitrsa = $this->Detaildroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $dossier_id
					),
					'contain' => array( 'Detailcalculdroitrsa' )
				)
			) ;

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'detaildroitrsa', $detaildroitrsa );
		}

		/**
		 *
		 * @param integer $detaildroitrsa_id
		 */
		/*public function view( $detaildroitrsa_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $detaildroitrsa_id ), 'error404' );

			$detaildroitrsa = $this->Detaildroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Detaildroitrsa.id' => $detaildroitrsa_id
					),
				'recursive' => -1
				)
			);

			$this->assert( !empty( $detaildroitrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $detaildroitrsa['Detaildroitrsa']['dossier_id'] );
			$this->set( 'detaildroitrsa', $detaildroitrsa );
		}*/
	}

?>