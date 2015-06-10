<?php
	/**
	 * Code source de la classe Decisionscuis66.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Decisionscuis66 ...
	 *
	 * @package app.Controller
	 */
	class Decisionscuis66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Decisionscuis66';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Decisioncui66', 'Option' );
		
		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'DossiersMenus',
			'Fileuploader',
			'Gestionzonesgeos',
			'Jetons2',
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
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
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
			'filelink' => 'view',
			'ajaxfileupload' => 'add',
			'ajaxfiledelete' => 'delete',
			'fileview' => 'view',
			'download' => 'view',
		);
		
		/**
		 * Envoi d'un fichier temporaire depuis le formualaire.
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * Suppression d'un fichier temporaire.
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Visualisation d'un fichier temporaire.
		 *
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Visualisation d'un fichier stocké.
		 *
		 * @param integer $id
		 */
		public function download( $id ) {
			$this->Fileuploader->download( $id );
		}

		/**
		 * Liste des fichiers liés à une orientation.
		 *
		 * @param integer $id
		 */
		public function filelink( $id ) {
			$query = array(
				'fields' => array(
					'Cui.personne_id',
					'Cui.id'
				),
				'joins' => array(
					$this->Decisioncui66->join( 'Cui66' ),
					$this->Decisioncui66->Cui66->join( 'Cui' ),
				),
				'conditions' => array( 'Decisioncui66.id' => $id )
			);
			$result = $this->Decisioncui66->find( 'first', $query );
			$personne_id = $result['Cui']['personne_id'];
			$cui_id = $result['Cui']['id'];
			
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->Fileuploader->filelink( $id, array( 'action' => 'index', $cui_id ) );
			$urlmenu = "/cuis66/index/{$personne_id}";
			
			$options = $this->Decisioncui66->enums();
			$this->set( compact( 'options', 'dossierMenu', 'urlmenu' ) );
		}
		
		/**
		 * Liste des Decisions du CUI du bénéficiaire.
		 * 
		 * @param integer $cui_id
		 */
		public function index( $cui_id ) {
			$params = array(
				'modelClass' => 'Decisioncui66',
				'view' => 'index',
				'urlmenu' => "/Cuis66/index/#0.Cui.personne_id#"
			);
			$customQuery['fields'][] = $this->Decisioncui66->Fichiermodule->sqNbFichiersLies( $this->Decisioncui66, 'nombre' );
			
			$this->WebrsaModelesLiesCuis66->index( $cui_id, $params, $customQuery );
		}
			
		/**
		 * Formulaire d'ajout de decisions du CUI
		 *
		 * @param integer $cui_id L'id du CUI
		 */
		public function add( $cui_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}
		
		/**
		 * Méthode générique d'ajout et de modification d'une decisions
		 *
		 * @param integer $id L'id du CUI (add) ou de la proposition (edit)
		 */
		public function edit( $id = null ) {
			$params = array(
				'modelClass' => 'Decisioncui66',
				'view' => 'edit',
				'redirect' => "/Decisionscuis66/index/#Cui.id#",
				'urlmenu' => "/Cuis66/index/#Cui.personne_id#"
			);
			$this->WebrsaModelesLiesCuis66->addEdit( $id, $params );
			$results = $this->Decisioncui66->getPropositions( $id, $this->action );
			$this->set ( compact( 'results' ) );
		}
		
		/**
		 * Suppression d'une décision
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function delete( $id ){
			return $this->WebrsaModelesLiesCuis66->delete( $id );
		}
		
		
		/**
		 * Impression générique
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression( $id ){
			return $this->WebrsaModelesLiesCuis66->impression( $id );
		}
		
		/**
		 * Impression decision élu
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression_decisionelu( $id ){
			return $this->WebrsaModelesLiesCuis66->impression( $id, 'decisionelu' );
		}
		
		/**
		 * Impression notification bénéficiaire
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression_notifbenef( $id ){
			$etatdossiercui66 = $this->Decisioncui66->getDecision( $id );
			
			$modeleOdt = $etatdossiercui66 === 'accord' ? 'notifbenef_accord' : 'notifbenef_refus';
					
			return $this->WebrsaModelesLiesCuis66->impression( $id, $modeleOdt );
		}
		
		/**
		 * Impression notification employeur
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression_notifemployeur( $id ){
			$etatdossiercui66 = $this->Decisioncui66->getDecision( $id );
			
			$modeleOdt = $etatdossiercui66 === 'accord' ? 'notifemployeur_accord' : 'notifemployeur_refus';
					
			return $this->WebrsaModelesLiesCuis66->impression( $id, $modeleOdt );
		}
		
		/**
		 * Impression attestation de compétence
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression_attestationcompetence( $id ){
			return $this->WebrsaModelesLiesCuis66->impression( $id, 'attestationcompetence' );
		}
	}
?>
