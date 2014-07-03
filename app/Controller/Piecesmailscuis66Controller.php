<?php
	/**
	 * Code source de la classe Piecesmailscuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Piecesmailscuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Piecesmailscuis66Controller extends AppController
	{
		public $name = 'Piecesmailscuis66';
		public $uses = array( 'Piecemailcui66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Piecesmailscuis66:index'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Piecemailcui66->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Piecemailcui66->qdOccurencesExists(
                array(
                    'fields' => $this->Piecemailcui66->fields(),
                    'order' => array( 'Piecemailcui66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $piecesmailscuis66 = $this->paginate('Piecemailcui66');
            $this->set( compact('piecesmailscuis66'));
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
                $this->redirect( array( 'controller' => 'piecesmailscuis66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Piecemailcui66->create( $this->request->data );
				$success = $this->Piecemailcui66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Piecemailcui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Piecemailcui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}

			$this->render( 'add_edit' );
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