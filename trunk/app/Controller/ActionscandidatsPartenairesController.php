<?php
	/**
	 * Code source de la classe ActionscandidatsPartenairesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ActionscandidatsPartenairesController ...
	 *
	 * @package app.Controller
	 */
	class ActionscandidatsPartenairesController extends AppController
	{

		public $name = 'ActionscandidatsPartenaires';
		public $uses = array( 'ActioncandidatPartenaire', 'Actioncandidat', 'Partenaire', 'Option', 'Personne' );
		public $helpers = array( 'Xform', 'Default', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'ActionscandidatsPartenaires:index',
			'add' => 'ActionscandidatsPartenaires:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();

			$options = array();
			foreach( array( 'Actioncandidat', 'Partenaire' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}

			$this->set( compact( 'options' ) );

			return $return;
		}

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
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