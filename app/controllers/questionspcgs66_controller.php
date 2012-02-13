<?php
	class Questionspcgs66Controller extends AppController
	{

		public $name = 'Questionspcgs66';
		public $helpers = array( 'Xform', 'Default2' );
		
		public $commeDroit = array(
			'add' => 'Questionspcgs66:edit'
		);
		
		protected function _setOptions() {
			$options = $this->Questionpcg66->enums();
			$options['Decisionpcg66'] = $this->Questionpcg66->Decisionpcg66->find( 'list' );
			$options['Compofoyerpcg66'] = $this->Questionpcg66->Compofoyerpcg66->find( 'list' );
			$this->set( compact( 'options' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}

			$questionspcgs66 = $this->Questionpcg66->find(
				'all',
				array(
					'contain' => array(
						'Decisionpcg66',
						'Compofoyerpcg66'
					),
					'order' => array( 'Questionpcg66.id ASC' )
				)
			);
			$this->set('questionspcgs66', $questionspcgs66);

			$compteurs = array(
				'Decisionpcg66' => $this->Questionpcg66->Decisionpcg66->find( 'count' ),
				'Compofoyerpcg66' => $this->Questionpcg66->Compofoyerpcg66->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );

			$this->_setOptions();
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

		protected function _add_edit( $questionpcg66_id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Questionpcg66->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'questionspcgs66', 'action' => 'index' ) );
				}
			}
			elseif ( $this->action == 'edit' ) {
				$questionpcg66 = $this->Questionpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Questionpcg66.id' => $questionpcg66_id,
						)
					)
				);
				$this->data = $questionpcg66;
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
				$this->redirect( array( 'controller' => 'decisionspdos', 'action' => 'index' ) );
			}
		}
	}

?>
