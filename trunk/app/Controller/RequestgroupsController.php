<?php
	/**
	 * Code source de la classe RequestgroupsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe RequestgroupsController ...
	 *
	 * @package app.Controller
	 */
	class RequestgroupsController extends AppController
	{
		public $name = 'Requestgroups';
		public $uses = array( 'Requestgroup' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Requestgroup->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Requestgroup->qdOccurencesExists(
                array(
                    'fields' => $this->Requestgroup->fields(),
                    'order' => array( 'Requestgroup.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $requestgroups = $this->paginate('Requestgroup');
			$options = $this->_options();
            $this->set( compact('requestgroups', 'options'));
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		*
		*/

		public function edit( $id = null ) {
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'requestgroups', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Requestgroup->create( $this->request->data );
				$success = $this->Requestgroup->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Requestgroup->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Requestgroup.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Requestgroup']['actif'] = true;
			}
			
			$options = $this->_options();
			
			$this->set( compact( 'options' ) );

			$this->view = 'edit';
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
		
		protected function _options() {
			$options['Requestgroup']['parent_id'] = $this->Requestgroup->find('list');
			
			return $options;
		}
	}
?>