<?php    
    /**
     * Code source de la classe Motifscersnonvalids66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Motifscersnonvalids66Controller ...
     *
     * @package app.Controller
     */
    class Motifscersnonvalids66Controller extends AppController
    {
        public $name = 'Motifscersnonvalids66';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Motifscersnonvalids66:index',
			'add' => 'Motifscersnonvalids66:edit'
		);



        public function index() {
			$queryData = array(
				'Motifcernonvalid66' => array(
					'fields' => array(
						'Motifcernonvalid66.id',
						'Motifcernonvalid66.name'
					),
					'contain' => false,
					'recursive' => -1,
					'group' => array(  'Motifcernonvalid66.id', 'Motifcernonvalid66.name' ),
					'order' => array( 'Motifcernonvalid66.name ASC' )
				)
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
