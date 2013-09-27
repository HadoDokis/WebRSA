<?php    
    /**
     * Code source de la classe Progsfichescandidatures66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */
    App::import('Behaviors', 'Occurences');

    /**
     * La classe Progsfichescandidatures66Controller ...
     *
     * @package app.Controller
     */
    class Progsfichescandidatures66Controller extends AppController
    {
        public $name = 'Progsfichescandidatures66';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Progsfichescandidatures66:index',
			'add' => 'Progsfichescandidatures66:edit'
		);


        
        protected function _setOptions(){
            $options = $this->Progfichecandidature66->enums();
            $this->set( compact( 'options' ) );
        }
        
        
        public function index() {
            $this->Progfichecandidature66->Behaviors->attach( 'Occurences' );
            $querydata = $this->Progfichecandidature66->qdOccurencesExists(
                array(
                    'fields' => array_merge(
                        $this->Progfichecandidature66->fields()
                    ),
                    'order' => array( 'Progfichecandidature66.name ASC' )
                )
            );
            $this->paginate = $querydata;
            $progsfichescandidatures66 = $this->paginate( 'Progfichecandidature66' );

            $this->_setOptions();
            $this->set( compact('progsfichescandidatures66'));
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

            $this->_setOptions();
            $this->Default->{$this->action}( $args );
        }

        /**
        *
        */

        public function delete( $id ) {
            $this->Default->delete( $id, true );
        }

        /**
        *
        */

        public function view( $id ) {
            $this->_setOptions();
            $this->Default->view( $id );
        }
    }
?>