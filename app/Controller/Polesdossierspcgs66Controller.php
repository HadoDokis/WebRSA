<?php
	/**
	 * Code source de la classe Polesdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

//    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Polesdossierspcgs66Controller ...
	 *
	 * @package app.Controller
	 */
	class Polesdossierspcgs66Controller extends AppController
	{
		public $name = 'Polesdossierspcgs66';
		public $uses = array( 'Poledossierpcg66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Polesdossierspcgs66:index',
			'add' => 'Polesdossierspcgs66:edit'
		);

        
        protected function _setOptions() {
            $options = array();
            $options = $this->Poledossierpcg66->enums();
            $originespdos = $this->Poledossierpcg66->Originepdo->find( 'list' );
            $typespdos = $this->Poledossierpcg66->Typepdo->find( 'list' );

            $this->set( compact( 'options', 'originespdos', 'typespdos' ) );
        }
		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
          
            $this->Poledossierpcg66->Behaviors->attach( 'Occurences' );
            $querydata = $this->Poledossierpcg66->qdOccurencesExists(
                    array(
                    'fields' => array_merge(
                        $this->Poledossierpcg66->fields(),
                        $this->Poledossierpcg66->Originepdo->fields(),
                        $this->Poledossierpcg66->Typepdo->fields()
                    ),
                    'joins' => array(
                        $this->Poledossierpcg66->join('Originepdo', array( 'type' => 'LEFT OUTER' ) ),
                        $this->Poledossierpcg66->join('Typepdo', array( 'type' => 'LEFT OUTER' ) ),
                    ),
                    'order' => array( 'Poledossierpcg66.name ASC' )
                )
            );
            $this->paginate = $querydata;
			$polesdossierspcgs66 = $this->paginate( 'Poledossierpcg66' );            
            
            $this->set( compact( 'polesdossierspcgs66' ) );
            
			$this->_setOptions();
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

		public function edit( $id = null){
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'polesdossierspcgs66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Poledossierpcg66->create( $this->request->data );
				$success = $this->Poledossierpcg66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Poledossierpcg66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Poledossierpcg66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
            $this->_setOptions();

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