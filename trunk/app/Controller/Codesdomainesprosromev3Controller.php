<?php

/**
 * Code source de la classe Codesdomainesprosromev3Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
//    App::import( 'Behaviors', 'Occurences' );
/**
 * La classe Codesdomainesprosromev3Controller ...
 *
 * @package app.Controller
 */
class Codesdomainesprosromev3Controller extends AppController {

    public $name = 'Codesdomainesprosromev3';
    public $uses = array('Codedomaineproromev3', 'Option');
    public $helpers = array('Xform', 'Default', 'Default2', 'Theme');
    public $components = array('Default');
    public $commeDroit = array(
        'view' => 'Codesdomainesprosromev3:index',
        'add' => 'Codesdomainesprosromev3:edit'
    );

    protected function _setOptions(){
        $options = array();
        $options = $this->Codedomaineproromev3->enums();
        $codesfamillesromev3 = $this->Codedomaineproromev3->Codefamilleromev3->find( 'list', array( 'fields' => array( 'Codefamilleromev3.fullname') ) );
        
        $this->set( compact( 'options', 'codesfamillesromev3' ) );
    }
    /**
     *   Ajout à la suite de l'utilisation des nouveaux helpers
     *   - default.php
     *   - theme.php
     */
    public function index() {
        $this->Codedomaineproromev3->Behaviors->attach('Occurences');
        $querydata = $this->Codedomaineproromev3->qdOccurencesExists(
            array(
                'fields' => array_merge(
                    $this->Codedomaineproromev3->fields(),
                    $this->Codedomaineproromev3->Codefamilleromev3->fields(),
                    array(
                        $this->Codedomaineproromev3->Codefamilleromev3->sqVirtualField( 'fullname', false )
                    )
                ),
                'joins' => array(
                    $this->Codedomaineproromev3->join( 'Codefamilleromev3', array( 'type' => 'INNER' ) )
                ),
                'order' => array('Codedomaineproromev3.name ASC'),
                'group' => array(),
                'contain' => false
            )
        );

        $querydata['fields'][] = $this->Codedomaineproromev3->Codefamilleromev3->sqVirtualField( 'fullname' );

        $this->paginate = $querydata;
        $codesdomainesprosromev3 = $this->paginate('Codedomaineproromev3');

        $this->_setOptions();
        $this->set(compact('codesdomainesprosromev3'));
    }

    /**
     *
     */
    public function add() {
        $args = func_get_args();
        call_user_func_array(array($this, 'edit'), $args);
    }

    /**
     *
     */
    public function edit($id = null) {
        // Retour à la liste en cas d'annulation
        if (isset($this->request->data['Cancel'])) {
            $this->redirect(array('controller' => 'codesdomainesprosromev3', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->Codedomaineproromev3->create($this->request->data);
            $success = $this->Codedomaineproromev3->save();

            $this->_setFlashResult('Save', $success);
            if ($success) {
                $this->redirect(array('action' => 'index'));
            }
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Codedomaineproromev3->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array('Codedomaineproromev3.id' => $id)
                )
            );
            $this->assert(!empty($this->request->data), 'error404');
        }

        $this->_setOptions();
        $this->render('edit');
    }

    /**
     *
     */
    public function delete($id) {
        $this->Default->delete($id);
    }

    /**
     *
     */
    public function view($id) {
        $this->Default->view($id);
    }

}

?>