<?php
	/**
	 * Code source de la classe InformationsetiController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InformationsetiController ...
	 *
	 * @package app.Controller
	 */
	class InformationsetiController extends AppController
	{
		public $name = 'Informationseti';

		public $uses = array( 'Informationeti',  'Option' , 'Personne' );

		public $components = array( 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'view' => 'Informationseti:index'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'' => '',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'topcreaentre', $this->Option->topcreaentre() );
			$this->set( 'topaccre', $this->Option->topaccre() );
			$this->set( 'acteti', ClassRegistry::init('Informationeti')->enum('acteti') );
			$this->set( 'topempl1ax', $this->Option->topempl1ax() );
			$this->set( 'topstag1ax', $this->Option->topstag1ax() );
			$this->set( 'topsansempl', $this->Option->topsansempl() );
			$this->set( 'regfiseti', $this->Option->regfiseti() );
			$this->set( 'topbeneti', $this->Option->topbeneti() );
			$this->set( 'regfisetia1', $this->Option->regfisetia1() );
			$this->set( 'topevoreveti', $this->Option->topevoreveti() );
			$this->set( 'topressevaeti', $this->Option->topressevaeti() );
		}

		/**
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ){
			$this->assert( valid_int( $personne_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$informationeti = $this->Informationeti->find(
				'first',
				array(
					'conditions' => array(
						'Informationeti.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			) ;

			// Assignations à la vue
			$this->set( 'personne_id', $personne_id );
			$this->set( 'informationeti', $informationeti );
		}

		/**
		 *
		 * @param integer $informationeti_id
		 */
		public function view( $informationeti_id = null ) {
			$this->assert( valid_int( $informationeti_id ), 'error404' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Informationeti->personneId( $informationeti_id ) ) ) );

			$informationeti = $this->Informationeti->find(
				'first',
				array(
					'conditions' => array(
						'Informationeti.id' => $informationeti_id
					),
				'recursive' => -1
				)
			);
			$this->assert( !empty( $informationeti ), 'error404' );

			// Assignations à la vue
			$this->set( 'personne_id', $informationeti['Informationeti']['personne_id'] );
			$this->set( 'informationeti', $informationeti );
		}
	}

?>