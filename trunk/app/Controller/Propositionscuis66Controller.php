<?php
	/**
	 * Code source de la classe Propositionscuis66.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Propositionscuis66 ...
	 *
	 * @package app.Controller
	 */
	class Propositionscuis66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Propositionscuis66';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Propositioncui66', 'Option' ); // FIXME: pour Cui66, passer par Propo...
		
		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'DossiersMenus',
			'Gestionzonesgeos',
			//'Gedooo.Gedooo',
			//'InsertionsAllocataires',
			'Jetons2', // FIXME: à cause de DossiersMenus
			//'Search.Filtresdefaut' => array( 'search' ),
			/*'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array( 'filter' => 'Search' ),
				)
			),*/
			'WebrsaModelesLiesCuis66',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Ajax2' => array(
				'className' => 'Prototype.PrototypeAjax',
				'useBuffer' => false
			),
			//'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			//'Search.SearchForm',
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => true
			),
			'Romev3', 'Cake1xLegacy.Ajax'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'add' => 'create',
			'edit' => 'update',
			'delete' => 'delete',
			'view' => 'read',
		);
		
		
		/**
		 * 
		 * @param integer $cui_id
		 */
		public function index( $cui_id ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'urlmenu' => "/Cuis66/index/#0.Cui.personne_id#"
			);
			return $this->WebrsaModelesLiesCuis66->index( $cui_id, $params );
		}
		
		/**
		 * 
		 * @param integer $id
		 */
		public function view( $id ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'urlmenu' => "/Cuis66/index/#Cui.personne_id#"
			);
			return $this->WebrsaModelesLiesCuis66->view( $id, $params );
		}
			
		/**
		 * Formulaire d'ajout d'avis technique CUI
		 *
		 * @param integer $cui_id L'id du CUI
		 */
		public function add( $cui_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}
		
		/**
		 * Méthode générique d'ajout et de modification d'avis technique
		 *
		 * @param integer $id L'id du CUI (add) ou de la proposition (edit)
		 */
		public function edit( $id = null ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'view' => 'edit',
				'redirect' => "/Propositionscuis66/index/#Cui.id#",
				'urlmenu' => "/Cuis66/index/#Cui.personne_id#"
			);
			return $this->WebrsaModelesLiesCuis66->addEdit( $id, $params );
		}
		
		public function delete( $id ){
			return $this->WebrsaModelesLiesCuis66->delete( $id );
		}
	}
?>
