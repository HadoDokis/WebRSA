<?php    
    /**
     * Code source de la classe Typesrsapcgs66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */
    App::import('Behaviors', 'Occurences');

    /**
     * La classe Typesrsapcgs66Controller ...
     *
     * @package app.Controller
     */
    class Typesrsapcgs66Controller extends AppController
    {
        public $name = 'Typesrsapcgs66';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Typesrsapcgs66:index',
			'add' => 'Typesrsapcgs66:edit'
		);


        
        public function index() {
            $this->Typersapcg66->Behaviors->attach( 'Occurences' );
            $querydata = $this->Typersapcg66->qdOccurencesExists(
                array(
                    'fields' => array_merge(
                        $this->Typersapcg66->fields()
                    ),
                    'order' => array( 'Typersapcg66.name ASC' )
                )
            );
            $this->paginate = $querydata;
            $typesrsapcgs66 = $this->paginate( 'Typersapcg66' );

            $this->set( compact('typesrsapcgs66'));
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
            $this->Default->view( $id );
        }
    }
?>
