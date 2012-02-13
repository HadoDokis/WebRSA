<?php
	class SuivisaidesapresController extends AppController
	{

		public $name = 'Suivisaidesapres';
		public $uses = array( 'Suiviaideapre', 'Option' );
		public $helpers = array( 'Xform' );
		
		public $commeDroit = array(
			'add' => 'Suivisaidesapres:edit'
		);

		public function beforeFilter() {
			$this->set( 'qual', $this->Option->qual() );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
			}

			$suivisaidesapres = $this->Suiviaideapre->find(
				'all',
				array(
					'recursive' => -1,
					'conditions' => array( 'Suiviaideapre.deleted' => '0' )
				)
			);
			$this->set('suivisaidesapres', $suivisaidesapres );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit() {
			$args = func_get_args();
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			call_user_func_array( array( $this->Default, $this->action ), $args );
		}

		/**
		*
		*/

		public function delete( $suiviaideapre_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $suiviaideapre_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$suiviaideapre = $this->Suiviaideapre->find(
				'first',
				array( 'conditions' => array( 'Suiviaideapre.id' => $suiviaideapre_id )
				)
			);

			// Mauvais paramètre
			if( empty( $suiviaideapre_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			$this->Suiviaideapre->delete( array( 'Suiviaideapre.id' => $suiviaideapre_id ) );
			$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			$this->redirect( array( 'controller' => 'suivisaidesapres', 'action' => 'index' ) );
		}
	}

?>