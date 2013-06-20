<?php
/**
 * Code source de la classe Motifssuspensioncuis66Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Motifssuspensioncuis66Controller ...
 *
 * @package app.Controller
 */
class Motifssuspensioncuis66Controller extends AppController
{
    public $name = 'Motifssuspensioncuis66';
    public $uses = array( 'Motifsuspensioncui66', 'Option' );
    public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
    public $components = array( 'Default' );

    public $commeDroit = array(
        'view' => 'Motifssuspensioncuis66:index',
        'add' => 'Motifssuspensioncuis66:edit'
    );

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

    protected function _add_edit( $id = null){
        // Retour à la liste en cas d'annulation
        if( isset( $this->request->data['Cancel'] ) ) {
            $this->redirect( array( 'controller' => 'motifssuspensioncuis66', 'action' => 'index' ) );
        }

        if( !empty( $this->request->data ) ) {
            $this->Motifsuspensioncui66->create( $this->request->data );
            $success = $this->Motifsuspensioncui66->save();

            $this->_setFlashResult( 'Save', $success );
            if( $success ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
        }
        else if( $this->action == 'edit' ) {
            $this->request->data = $this->Motifsuspensioncui66->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array( 'Motifsuspensioncui66.id' => $id )
                )
            );
            $this->assert( !empty( $this->request->data ), 'error404' );
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