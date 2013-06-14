<?php    
    /**
     * Code source de la classe SecteurscuisController.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */
     App::import( 'Behaviors', 'Occurences' );

    /**
     * La classe SecteurscuisController ...
     *
     * @package app.Controller
     */
    class SecteurscuisController extends AppController
    {
        public $name = 'Secteurscuis';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Secteurscuis:index',
			'add' => 'Secteurscuis:edit'
		);


		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$options = array(
				'exists' => array( '0' => 'Non', '1' => 'Oui' )
			);
			$this->set( compact( 'options' ) );
		}

        /**
         * 
         */
        public function index() {
			$this->Secteurcui->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Secteurcui->qdOccurencesExists(
                array(
                    'fields' => $this->Secteurcui->fields(),
                    'order' => array( 'Secteurcui.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $secteurscuis = $this->paginate('Secteurcui');
            $this->set( compact('secteurscuis'));
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

        protected function _add_edit(){
            $args = func_get_args();

            // Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'secteurscuis', 'action' => 'index' ) );
			}
            $this->_setOptions();
            $this->Default->{$this->action}( $args );
        }

        /**
         * 
         * @param integer $id
         */
        public function delete( $id ) {
            $this->Default->delete( $id, true );
        }

        /**
        *
        */

        public function view( $id ) {
            $this->Default->view( $id );
        }
    }
?>
