<?php

/**
 * Code source de la classe Codesmetiersromev3Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
//    App::import( 'Behaviors', 'Occurences' );
/**
 * La classe Codesmetiersromev3Controller ...
 *
 * @package app.Controller
 */
class Codesmetiersromev3Controller extends AppController {

    public $name = 'Codesmetiersromev3';
    public $uses = array('Codemetierromev3', 'Option');
    public $helpers = array('Xform', 'Default', 'Default2', 'Theme');
    public $components = array('Default');
    public $commeDroit = array(
        'view' => 'Codesmetiersromev3:index',
        'add' => 'Codesmetiersromev3:edit'
    );

    protected function _setOptions(){
        $options = array();
        $options = $this->Codemetierromev3->enums();
        $codesdomainesprosromev3 = $this->Codemetierromev3->Codedomaineproromev3->findListParametrage();
        $this->set( compact( 'options', 'codesdomainesprosromev3' ) );
    }
    /**
     *   Ajout à la suite de l'utilisation des nouveaux helpers
     *   - default.php
     *   - theme.php
     */
    public function index() {

        $this->Codemetierromev3->Behaviors->attach('Occurences');
        $querydata = $this->Codemetierromev3->qdOccurencesExists(
            array(
                'fields' => array_merge(
                    $this->Codemetierromev3->fields(),
                    $this->Codemetierromev3->Codedomaineproromev3->fields(),
                    $this->Codemetierromev3->Codedomaineproromev3->Codefamilleromev3->fields(),
                    array(
                        $this->Codemetierromev3->Codedomaineproromev3->sqVirtualField( 'fullname', false )
                    )
                ),
                'joins' => array(
                    $this->Codemetierromev3->join( 'Codedomaineproromev3', array( 'type' => 'INNER') ),
                    $this->Codemetierromev3->Codedomaineproromev3->join( 'Codefamilleromev3', array( 'type' => 'INNER') )
                ),
                'order' => array('Codemetierromev3.name ASC')
            )
        );
        
        $querydata['fields'][] = $this->Codemetierromev3->Codedomaineproromev3->sqVirtualField( 'fullname');
        $this->paginate = $querydata;
        $codesmetiersromev3 = $this->paginate('Codemetierromev3');

        $this->_setOptions();
        $this->set(compact('codesmetiersromev3'));
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
            $this->redirect(array('controller' => 'codesmetiersromev3', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->Codemetierromev3->create($this->request->data);
            $success = $this->Codemetierromev3->save();

            $this->_setFlashResult('Save', $success);
            if ($success) {
                $this->redirect(array('action' => 'index'));
            }
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Codemetierromev3->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array('Codemetierromev3.id' => $id)
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