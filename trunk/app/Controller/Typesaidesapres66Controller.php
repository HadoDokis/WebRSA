<?php
    /**
     * Code source de la classe Typesaidesapres66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Typesaidesapres66Controller ...
     *
     * @package app.Controller
     */
    class Typesaidesapres66Controller extends AppController
    {
        public $name = 'Typesaidesapres66';

        public $helpers = array( 'Default', 'Default2' );

        public $uses = array( 'Typeaideapre66', 'Themeapre66', 'Pieceaide66', 'Piececomptable66', 'Nameapre66' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Typesaidesapres66:index',
			'add' => 'Typesaidesapres66:edit'
		);

        /**
        *
        */

        public function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            $options = $this->{$this->modelClass}->enums();

            foreach( array( 'Themeapre66' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }

            $this->set( compact( 'options' ) );

            $pieceadmin = $this->Pieceaide66->find(
                'list',
                array(
                    'fields' => array(
                        'Pieceaide66.id',
                        'Pieceaide66.name'
                    )
                )
            );
            $this->set( 'pieceadmin', $pieceadmin );
            ///TODO: ajouter les pieces comtpables
            $piececomptable = $this->Piececomptable66->find(
                'list',
                array(
                    'fields' => array(
                        'Piececomptable66.id',
                        'Piececomptable66.name'
                    )
                )
            );
            $this->set( 'piececomptable', $piececomptable );
			
			$this->set('namesapres', $this->Nameapre66->find('list'));

            return $return;
        }


//         public function index() {
//
// 			$this->set( 'occurences', $this->Typeaideapre66->occurences() );
// // 			debug( $this->Typeaideapre66->occurences() );
// 			$queryData = array(
// 				'Typeaideapre66' => array(
// 					'order' => array( 'Themeapre66.name ASC', 'Typeaideapre66.name ASC' )
// 				)
// 			);
//             $this->Default->index( $queryData );
//         }

        public function index() {

            // Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'typesaidesapres66', 'action' => 'index' ) );
			}

			App::import( 'Behaviors', 'Occurences' );
			$this->Typeaideapre66->Behaviors->attach( 'Occurences' );

			$this->set( 'occurences', $this->Typeaideapre66->occurences() );

			$queryData = array(
				'Typeaideapre66' => array(
					'fields' => array(
						'Typeaideapre66.id',
						'Typeaideapre66.name',
                        'Typeaideapre66.isincohorte',
						'Themeapre66.name',
						'COUNT("Aideapre66"."id") AS "Typeaideapre66__occurences"',
					),
					'joins' => array(
						$this->Typeaideapre66->join( 'Aideapre66' ),
						$this->Typeaideapre66->join( 'Themeapre66' ),
					),
					'recursive' => -1,
					'group' => array(  'Typeaideapre66.id', 'Typeaideapre66.name', 'Themeapre66.name', 'Typeaideapre66.isincohorte'  ),
					'order' => array( 'Themeapre66.name ASC', 'Typeaideapre66.name ASC' ),
                    'limit' => 50
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