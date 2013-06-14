<?php
	/**
	 * Fichier source de la classe TraitementstypespdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 */

	/**
	 * La classe TraitementstypespdosController fournit les méthodes de paramétrage
	 * des "Types de traitements PDO".
	 *
	 * @package app.Controller
	 */
	class TraitementstypespdosController extends AppController
	{
		/**
		 * Le nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Traitementstypespdos';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		/**
		 * Helpers utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $helpers = array( 'Default', 'Default2' );

		/**
		 * Équivalence des droits des actions de ce contrôleur.
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'view' => 'Traitementstypespdos:index',
			'add' => 'Traitementstypespdos:edit'
		);

		/**
		 * Liste des "Types de traitements PDO".
		 */
		public function index() {
			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->search(
				$this->request->data
			);
		}

		/**
		 * Ajout d'un "Type de traitement PDO".
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Modification d'un "Type de traitement PDO".
		 *
		 * @param integer $id L'id technique de l'enregistrement à modifier
		 */
		public function edit( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Méthode commune utilisée pour l'ajout ou la mModfication d'un
		 * "Type de traitement PDO".
		 *
		 * @param integer $id L'id technique de l'enregistrement à modifier
		 */
		protected function _add_edit( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->{$this->action}( $id );
		}

		/**
		 * Suppression d'un "Type de traitement PDO".
		 *
		 * @param integer $id L'id technique de l'enregistrement à supprimer
		 */
		public function delete( $id = null ) {
			$this->Default->delete( $id );
		}
	}
?>