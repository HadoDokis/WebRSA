<?php
/**
 * Code source de la classe Typescontratscuis66Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
    App::import( 'Behaviors', 'Occurences' );

/**
 * La classe Typescontratscuis66Controller ...
 *
 * @package app.Controller
 */
class Typescontratscuis66Controller extends AppController
{
    public $name = 'Typescontratscuis66';
    public $uses = array( 'Typecontratcui66', 'Option' );
    public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
    public $components = array( 'Default' );

    public $commeDroit = array(
        'view' => 'Typescontratscuis66:index',
        'add' => 'Typescontratscuis66:edit'
    );

    /**
     *   Ajout à la suite de l'utilisation des nouveaux helpers
     *   - default.php
     *   - theme.php
     */

    public function index() {
        $this->Typecontratcui66->Behaviors->attach( 'Occurences' );
  
        $querydata = $this->Typecontratcui66->qdOccurencesExists(
            array(
                'fields' => $this->Typecontratcui66->fields(),
                'order' => array( 'Typecontratcui66.name ASC' )
            )
        );

        $this->paginate = $querydata;
        $typescontratscuis66 = $this->paginate('Typecontratcui66');
        $this->set( compact('typescontratscuis66'));

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

    protected function _add_edit( $id = null){
        // Retour à la liste en cas d'annulation
        if( isset( $this->request->data['Cancel'] ) ) {
            $this->redirect( array( 'controller' => 'typescontratscuis66', 'action' => 'index' ) );
        }

        if( !empty( $this->request->data ) ) {
            $this->Typecontratcui66->create( $this->request->data );
            $success = $this->Typecontratcui66->save();

            $this->_setFlashResult( 'Save', $success );
            if( $success ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
        }
        else if( $this->action == 'edit' ) {
            $this->request->data = $this->Typecontratcui66->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array( 'Typecontratcui66.id' => $id )
                )
            );
            $this->assert( !empty( $this->request->data ), 'error404' );
        }
		else{
			$this->request->data['Typecontratcui66']['actif'] = true;
		}

        $this->render( 'add_edit' );
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