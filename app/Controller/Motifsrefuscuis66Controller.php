<?php
	/**
	 * Code source de la classe Motifsrefuscuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Motifsrefuscuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Motifsrefuscuis66Controller extends AppController
	{
		public $name = 'Motifsrefuscuis66';
		public $uses = array( 'Motifrefuscui66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Motifsrefuscuis66:index',
			'add' => 'Motifsrefuscuis66:edit'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Motifrefuscui66->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Motifrefuscui66->qdOccurencesExists(
                array(
                    'fields' => $this->Motifrefuscui66->fields(),
                    'order' => array( 'Motifrefuscui66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $motifsrefuscuis66 = $this->paginate('Motifrefuscui66');
            $this->set( compact('motifsrefuscuis66'));
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
                $this->redirect( array( 'controller' => 'motifsrefuscuis66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Motifrefuscui66->create( $this->request->data );
				$success = $this->Motifrefuscui66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Motifrefuscui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifrefuscui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Motifrefuscui66']['actif'] = true;
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