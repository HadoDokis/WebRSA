<?php
	/**
	 * Code source de la classe ContactspartenairesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
     App::import('Behaviors', 'Occurences');

	/**
	 * La classe ContactspartenairesController ...
	 *
	 * @package app.Controller
	 */
	class ContactspartenairesController extends AppController
	{
		public $name = 'Contactspartenaires';
		public $uses = array( 'Contactpartenaire', 'Partenaire', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default', 'Search.SearchPrg' => array( 'actions' => array( 'index' ) ) );

		public $commeDroit = array(
			'view' => 'Contactspartenaires:index',
			'add' => 'Contactspartenaires:edit'
		);

		/**
		 *
		 * @return type
		 */
		public function _setOptions() {
			$options = array();
			foreach( array( 'Partenaire' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list', array( 'order' => array( "{$linkedModel}.libstruc ASC" ) ) ) );
			}
			$this->set( 'qual', $this->Option->qual() );

			$this->set( compact( 'options', 'qual' ) );
		}


		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
//            $this->Contactpartenaire->Behaviors->attach( 'Occurences' );
//            $querydata = $this->Contactpartenaire->qdOccurencesExists(
//                array(
//                    'fields' => array_merge(
//                        $this->Contactpartenaire->fields(),
//                        $this->Contactpartenaire->Partenaire->fields()
//                    ),
//                    'order' => array('Contactpartenaire.nom ASC')
//                )
//            );
//            $this->paginate = $querydata;
//            $contactspartenaires = $this->paginate( 'Contactpartenaire' );
//            $this->_setOptions();
//            $this->set( compact('contactspartenaires'));
//
//		}
            
            if( !empty( $this->request->data ) ) {
                $this->Contactpartenaire->Behaviors->attach( 'Occurences' );
                $querydata = $this->Contactpartenaire->search( $this->request->data );
                $querydata = $this->Contactpartenaire->qdOccurencesExists( $querydata );
                $this->paginate = $querydata;
                $contactspartenaires = $this->paginate( 'Contactpartenaire' );
                $this->set( compact('contactspartenaires'));
			}
			$this->_setOptions();
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
