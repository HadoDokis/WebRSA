<?php    
    /**
     * Code source de la classe Typescourrierspcgs66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Typescourrierspcgs66Controller ...
     *
     * @package app.Controller
     */
    class Typescourrierspcgs66Controller extends AppController
    {
        public $name = 'Typescourrierspcgs66';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Typescourrierspcgs66:index',
			'add' => 'Typescourrierspcgs66:edit'
		);



        public function index() {
			$queryData = array(
				'fields' => array(
                    'Typecourrierpcg66.id',
                    'Typecourrierpcg66.name',
                    'COUNT("Traitementpcg66"."id") AS "Typecourrierpcg66__occurences"'
                ),
                'contain' => false,
                'joins' => array(
                    $this->Typecourrierpcg66->join( 'Traitementpcg66' )
                ),
                'recursive' => -1,
                'limit' => 20,
                'group' => array(  'Typecourrierpcg66.id', 'Typecourrierpcg66.name' ),
                'order' => array( 'Typecourrierpcg66.name ASC' )
                
			);
            $this->Default->index( $queryData );
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
