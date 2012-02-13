<?php
	class Composfoyerspcgs66Controller extends AppController
	{

		public $name = 'Composfoyerspcgs66';
		public $helpers = array( 'Xform', 'Default2' );
		
		public $commeDroit = array(
			'add' => 'Composfoyerspcgs66:edit'
		);
		
		protected function _setOptions() {
// 			$options = $this->Compofoyerpcg66->enums();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}

			$composfoyerspcgs66 = $this->Compofoyerpcg66->find(
				'all',
				array(
					'contain' => false,
					'order' => array( 'Compofoyerpcg66.id ASC' )
				)
			);

			$this->set('composfoyerspcgs66', $composfoyerspcgs66);
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

		protected function _add_edit( $compofoyerpcg66_id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Compofoyerpcg66->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'composfoyerspcgs66', 'action' => 'index' ) );
				}
			}
			elseif ( $this->action == 'edit' ) {
				$compofoyerpcg66 = $this->Compofoyerpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Compofoyerpcg66.id' => $compofoyerpcg66_id,
						)
					)
				);
				$this->data = $compofoyerpcg66;
			}
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
		}

		public function delete( $compofoyerpcg66_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $compofoyerpcg66_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$decisionpcg66 = $this->Compofoyerpcg66->find(
				'first',
				array( 'conditions' => array( 'Compofoyerpcg66.id' => $compofoyerpcg66_id )
				)
			);

			// Mauvais paramètre
			if( empty( $compofoyerpcg66_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Compofoyerpcg66->delete( array( 'Compofoyerpcg66.id' => $compofoyerpcg66_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'composfoyerspcgs66', 'action' => 'index' ) );
			}
		}
	}

?>
