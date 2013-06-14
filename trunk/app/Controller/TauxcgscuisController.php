<?php    
    /**
     * Code source de la classe TauxcgscuisController.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe TauxcgscuisController ...
     *
     * @package app.Controller
     */
    class TauxcgscuisController extends AppController
    {
        public $name = 'Tauxcgscuis';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Tauxcgscuis:index',
			'add' => 'Tauxcgscuis:edit'
		);


		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {

			$options = array(
				'Tauxcgcui' => array(
					'secteurcui_id' => $this->Tauxcgcui->Secteurcui->find( 'list', array( 'fields' => array( 'id', 'name' ) ) )
				)
			);
			$options = Set::merge(
				$options,
				$this->Tauxcgcui->enums()
			);
			$this->set( compact( 'options' ) );
			
			// Affichage des valeurs non marchnades si le secteur choisi est de type non marchand
			$valeursSecteurcui = $this->Tauxcgcui->Secteurcui->find(
				'all',
				array(
					'order' => array( 'Secteurcui.isnonmarchand DESC', 'Secteurcui.name ASC' )
				)
			);
			$secteur_isnonmarchand_id = Hash::extract( $valeursSecteurcui, '{n}.Secteurcui[isnonmarchand=1].id' );
			$this->set( compact( 'secteur_isnonmarchand_id' ) );
		}

        public function index() {

			$queryData = array(
				'Tauxcgcui' => array(
					'fields' => $this->Tauxcgcui->fields(),
					'order' => array( 'Tauxcgcui.name ASC' )
				)
			);
			$this->_setOptions();
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

            // Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'tauxcgscuis', 'action' => 'index' ) );
			}
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
