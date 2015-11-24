<?php
	/**
	 * Code source de la classe TagsController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe TagsController ...
	 *
	 * @package app.Controller
	 */
	class TagsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Tags';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes', 
			'Fileuploader', 
			'Gedooo.Gedooo', 
			'Jetons2', 
			'DossiersMenus',
			'Search.SearchPrg' => array(
				'actions' => array(
					'cohorte' => array(
						'filter' => 'Search'
					),
					'cohorte_heberge' => array(
						'filter' => 'Search'
					),
				)
			),
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Cake1xLegacy.Ajax',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Tag',
			'WebrsaCohorteTag'
		);
		
		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'cohorte' => 'update',
			'delete' => 'delete',
			'edit' => 'update',
			'index' => 'read',
		);

		/**
		 * Action d'ajout d'un tag à une personne
		 * 
		 * @param integer $id
		 */
		public function add( $modele, $id ) {
			// Initialisation
			$this->_init_add_edit($modele, $id);
			
			// Sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->_save_add_edit($modele, $id);
			}
			
			// Vue
			$this->view = 'edit';
		}
		
		/**
		 * Action d'edition du tag d'une personne
		 * 
		 * @param integer $tag_id
		 */
		public function edit( $tag_id ) {
			// Initialisation
			$result = $this->Tag->findTagById($tag_id);
			$this->assert( !empty( $result ), 'invalidParameter' );
			
			$id = Hash::get($result, 'Tag.fk_value');
			$modele = Hash::get($result, 'Tag.modele');
			$this->_init_add_edit($modele, $id);
			
			$this->set(
				compact(
					'result'
				) 
			);

			// Sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->_save_add_edit($modele, $id);
			}
			else {
				$this->request->data = $result;
			}
		}
		
		/**
		 * Initialisation du formulaire d'edition d'un tag
		 * Jeton et redirection en cas de retour
		 * 
		 * @param string $modele
		 * @param integer $id
		 */
		protected function _init_add_edit( $modele, $id ) {
			// Validité de l'url
			$this->assert( valid_int( $id ) && isset($this->Tag->{$modele}), 'invalidParameter' );
			
			// Gestion des jetons
			$dossier_id = $this->Tag->{$modele}->dossierId( $id );
			$this->Jetons2->get( $dossier_id );
			
			// Redirection si Cancel
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $modele, $id ) );
			}
			
			$urlmenu = implode('/', array( '', 'tags', 'index', $modele, $id ));
			
			// Variables pour la vue
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );
			$this->set( compact( 'personne_id', 'dossier_id', 'urlmenu' ) );
			
			$this->_setOptions();
		}
		
		/**
		 * Sauvegarde d'un formulaire add ou edit
		 * 
		 * @param integer $id
		 */
		protected function _save_add_edit( $modele, $id ) {
			$this->Tag->begin();

			$this->request->data['Tag']['fk_value'] = $id;
			$this->request->data['Tag']['modele'] = $modele;
			
			if( $this->Tag->save( $this->request->data ) ) {
				$this->Tag->commit();
				$this->Jetons2->release( $this->viewVars['dossier_id'] );
				$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
				$this->redirect( array(  'controller' => 'tags','action' => 'index', $modele, $id ) );
			}
			else {
				$id && $this->set('fichiers', $this->Fileuploader->fichiers( $id ));
				$this->Tag->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
			}
		}
		
		/**
		 * Liste des dossiers PCG d'un foyer
		 * 
		 * @param string $modele
		 * @param integer $id
		 */
		public function index( $modele, $id ) {
			$this->assert( valid_int( $id ) && isset($this->Tag->{$modele}), 'invalidParameter' );
			
			$dossier_id = $this->Tag->{$modele}->dossierId($id);
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );
			
			$results = $this->Tag->findTagModel( $modele, $id );
			
			$infos = $this->Tag->{$modele}->find('first', array('conditions' => array("{$modele}.id" => $id)));
			
			// Incrustation de texte dans la traduction
			switch ($modele) {
				case 'Personne': $infos['Info']['tag'] = 'de '.Hash::get($infos, 'Personne.nom_complet'); break;
				case 'Foyer': $infos['Info']['tag'] = 'du Foyer'; break;
				default: $infos['Info']['tag'] = ''; break;
			}
			
			$this->set( compact( 'results', 'dossier_id', 'id', 'modele', 'infos' ) );
			$this->_setOptions();
		}

		/**
		 * Suppression d'un <élément> et redirection vers l'index.
		 *
		 * @param integer $tag_id
		 */
		public function delete( $tag_id ) {
			$this->{$this->modelClass}->begin();

			if( $this->{$this->modelClass}->delete( $tag_id ) ) {
				$this->{$this->modelClass}->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->{$this->modelClass}->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
		
		/**
		 * Cohorte
		 */
		public function cohorte() {
			$this->WebrsaCohorteTag->cohorteFields = array(
				'Personne.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
				'Foyer.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
				'Tag.selection' => array( 'type' => 'checkbox', 'label' => '&nbsp;' ),
				'Tag.modele' => array( 'type' => 'select', 'label' => '' ),
				'Tag.valeurtag_id' => array( 'type' => 'select', 'label' => '' ),
				'Tag.limite' => array( 'type' => 'date', 'label' => '', 'dateFormat' => 'DMY', 'minYear' => date('Y'), 'maxYear' => date('Y')+4 ),
				'Tag.commentaire' => array( 'type' => 'textarea', 'label' => '' ),
			);
			$Recherches = $this->Components->load( 'WebrsaCohortesTagsNew' );
			$Recherches->cohorte( array( 'modelName' => 'Dossier' ) );
		}
		
		/**
		 * Parametrages liés
		 */
		public function indexparams() {}
		
		/**
		 * Annule un tag
		 * 
		 * @param integer $id
		 */
		public function cancel( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );
			
			$data = array(
				'id' => $id,
				'etat' => 'annule'
			);
			
			$this->{$this->modelClass}->begin();

			if( $this->{$this->modelClass}->save($data) ) {
				$this->{$this->modelClass}->commit();
				$this->Session->setFlash( 'Annulation effectuée', 'flash/success' );
			}
			else {
				$this->{$this->modelClass}->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'annulation', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
		
		/**
		 * Options à renvoyer à la vue
		 * 
		 * @return array
		 */
		protected function _setOptions() {
			$options = $this->Tag->enums();
			
			$results = $this->Tag->Valeurtag->find('all', array(
				'fields' => array(
					'Categorietag.name',
					'Valeurtag.id',
					'Valeurtag.name'
				),
				'joins' => array(
					$this->Tag->Valeurtag->join('Categorietag')
				),
			));
			
			foreach ($results as $value) {
				$categorie = Hash::get($value, 'Categorietag.name') ? Hash::get($value, 'Categorietag.name') : 'Sans catégorie';
				$valeur = Hash::get($value, 'Valeurtag.name');
				$valeurtag_id = Hash::get($value, 'Valeurtag.id');
				$options['Tag']['valeurtag_id'][$categorie][$valeurtag_id] = $valeur;
			}
			
			$this->set( compact( 'options' ) );
		}
	}
?>
