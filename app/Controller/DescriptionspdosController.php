<?php
	/**
	 * Fichier source de la classe DescriptionspdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 */

	/**
	 * La classe TraitementstypespdosController fournit les méthodes de paramétrage
	 * des "Description des traitements PDO".
	 *
	 * @package app.Controller
	 */
	class DescriptionspdosController extends AppController
	{
		/**
		 * Le nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Descriptionspdos';

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
		public $helpers = array( 'Default2' );

		/**
		 * Équivalence des droits des actions de ce contrôleur.
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'view' => 'Descriptionspdos:index',
			'add' => 'Descriptionspdos:edit'
		);

		/**
		 * Retourne les options à utiliser dans les formulaires.
		 *
		 * @return array
		 */
		protected function _options() {
			$options = $this->{$this->modelClass}->enums();
			return $options;
		}

		/**
		 * Liste des "Descriptions des traitements PDO".
		 */
		public function index() {
			$options = $this->_options();
			$this->set( 'options', $options );

            $this->paginate['recursive'] = -1;
			$queryData = $this->paginate( $this->modelClass );
            $queryData = array_merge(
                $queryData,
                $this->Descriptionpdo->qdOccurences()
            );
            
            $descriptionspdos = $this->Descriptionpdo->find( 'all', $queryData );
            
            $this->set( compact( 'descriptionspdos' ) );
//			$this->Default->search(
//				$this->request->data
//			);
		}

		/**
		 * Ajout d'une "Description de traitement PDO".
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Modification d'une "Description de traitement PDO".
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
			$options = $this->_options();
			$this->set( 'options', $options );

			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			$this->{$this->modelClass}->recursive = -1;
			$this->Default->{$this->action}( $id );

		}

		/**
		 * Suppression d'une "Description de traitement PDO".
		 *
		 * @param integer $id L'id technique de l'enregistrement à supprimer
		 */
		public function delete( $id = null ) {
			$this->Default->delete( $id );
		}
	}
?>