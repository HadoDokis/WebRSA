<?php
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

// 			$this->set( 'occurences', $this->Typersapcg66->occurences() );
// 			debug( $this->Typersapcg66->occurences() );
			$queryData = array(
				'Typersapcg66' => array(
					'fields' => array(
						'Typersapcg66.id',
						'Typersapcg66.name',
// 						'COUNT("Aideapre66"."id") AS "Typersapcg66__occurences"',
					),
					'contain' => false,
// 					'joins' => array(
// 						$this->Typersapcg66->join( 'Aideapre66' ),
// 						$this->Typersapcg66->join( 'Themeapre66' ),
// 					),
					'recursive' => -1,
					'group' => array(  'Typersapcg66.id', 'Typersapcg66.name' ),
					'order' => array( 'Typersapcg66.name ASC' )
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

        function _add_edit(){
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
