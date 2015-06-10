<?php
	/**
	 * Code source de la classe Piecesmanquantescuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Piecesmanquantescuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Piecesmanquantescuis66Controller extends AppController
	{
		public $name = 'Piecesmanquantescuis66';
		public $uses = array( 'Piecemanquantecui66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Piecesmanquantescuis66:index',
			'add' => 'Piecesmanquantescuis66:edit'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Piecemanquantecui66->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Piecemanquantecui66->qdOccurencesExists(
                array(
                    'fields' => $this->Piecemanquantecui66->fields(),
                    'order' => array( 'Piecemanquantecui66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $piecesmanquantescuis66 = $this->paginate('Piecemanquantecui66');
            $this->set( compact('piecesmanquantescuis66'));
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

		protected function _add_edit( $id = null){
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'piecesmanquantescuis66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Piecemanquantecui66->create( $this->request->data );
				$success = $this->Piecemanquantecui66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Piecemanquantecui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Piecemanquantecui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Piecemanquantecui66']['actif'] = true;
			}

			$this->render( 'edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->Default->view( $id );
		}
	}
?>