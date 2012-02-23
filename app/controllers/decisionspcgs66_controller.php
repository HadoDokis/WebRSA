<?php
	class Decisionspcgs66Controller extends AppController
	{

		public $name = 'Decisionspcgs66';
		public $helpers = array( 'Xform', 'Default2' );
		
		public $commeDroit = array(
			'add' => 'Decisionspcgs66:edit'
		);
		
		protected function _setOptions() {
// 			$options = $this->Decisionpcg66->enums();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}
			
			$qdOccurences = $this->Decisionpcg66->qdOccurences();

			$decisionspcgs66 = $this->Decisionpcg66->find( 'all', $qdOccurences );

			$this->set('decisionspcgs66', $decisionspcgs66);
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

		protected function _add_edit( $decisionpcg66_id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Decisionpcg66->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'decisionspcgs66', 'action' => 'index' ) );
				}
			}
			elseif ( $this->action == 'edit' ) {
				$decisionpcg66 = $this->Decisionpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Decisionpcg66.id' => $decisionpcg66_id,
						)
					)
				);
				$this->data = $decisionpcg66;
			}
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
		}

		public function delete( $decisionpcg66_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $decisionpcg66_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$decisionpcg66 = $this->Decisionpcg66->find(
				'first',
				array( 'conditions' => array( 'Decisionpcg66.id' => $decisionpcg66_id )
				)
			);

			// Mauvais paramètre
			if( empty( $decisionpcg66_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Decisionpcg66->delete( array( 'Decisionpcg66.id' => $decisionpcg66_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'decisionspcgs66', 'action' => 'index' ) );
			}
		}
	}

?>
