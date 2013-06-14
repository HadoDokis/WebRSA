<?php	
	/**
	 * Code source de la classe Aidesapres66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Aidesapres66Controller ...
	 *
	 * @package app.Controller
	 */
	class Aidesapres66Controller extends AppController
	{

		public $name = 'Aidesapres66';
		public $uses = array( 'Aideapre66', 'Themeapre66', 'Pieceaide66' );

		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Aidesapres66:index',
			'add' => 'Aidesapres66:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();

			$options = array();

			foreach( array( 'Themeapre66', 'Typeaideapre66' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}
			$this->set( compact( 'options' ) );

			$pieceliste = $this->Pieceaide66->find(
				'list',
				array(
					'fields' => array(
						'Pieceaide66.id',
						'Pieceaide66.name'
					)
				)
			);
			$this->set( 'pieceliste', $pieceliste );

			return $return;
		}

		public function index() {
			$this->Default->index();
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