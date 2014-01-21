<?php

/**
 * Code source de la classe Codesappellationsromev3Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
//    App::import( 'Behaviors', 'Occurences' );
/**
 * La classe Codesappellationsromev3Controller ...
 *
 * @package app.Controller
 */
class Codesappellationsromev3Controller extends AppController {

    public $name = 'Codesappellationsromev3';
    public $uses = array('Codeappellationromev3', 'Option');
    public $helpers = array('Xform', 'Default', 'Default2', 'Theme');
    public $components = array('Default');
    public $commeDroit = array(
        'view' => 'Codesappellationsromev3:index',
        'add' => 'Codesappellationsromev3:edit'
    );

    protected function _setOptions(){
        $codesmetiersromev3 = $this->Codeappellationromev3->Codemetierromev3->findListParametrage();
        $this->set( compact( 'codesmetiersromev3' ) );
    }
    /**
     *   Ajout à la suite de l'utilisation des nouveaux helpers
     *   - default.php
     *   - theme.php
     */
    public function index() {

        $this->Codeappellationromev3->Behaviors->attach('Occurences');
        $querydata = $this->Codeappellationromev3->qdOccurencesExists(
            array(
                'fields' => array_merge(
                    $this->Codeappellationromev3->fields(),
                    $this->Codeappellationromev3->Codemetierromev3->fields(),
                    $this->Codeappellationromev3->Codemetierromev3->Codedomaineproromev3->fields(),
                    $this->Codeappellationromev3->Codemetierromev3->Codedomaineproromev3->Codefamilleromev3->fields()
                ),
                'joins' => array(
                    $this->Codeappellationromev3->join( 'Codemetierromev3', array( 'type' => 'INNER') ),
                    $this->Codeappellationromev3->Codemetierromev3->join( 'Codedomaineproromev3', array( 'type' => 'INNER') ),
                    $this->Codeappellationromev3->Codemetierromev3->Codedomaineproromev3->join( 'Codefamilleromev3', array( 'type' => 'INNER') )
                ),
                'order' => array('Codeappellationromev3.name ASC')
            )
        );
        $this->paginate = $querydata;
        $codesappellationsromev3 = $this->paginate('Codeappellationromev3');

        $this->_setOptions();
        $this->set(compact('codesappellationsromev3'));
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
            $this->redirect(array('controller' => 'codesappellationsromev3', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->Codeappellationromev3->create($this->request->data);
            $success = $this->Codeappellationromev3->save();

            $this->_setFlashResult('Save', $success);
            if ($success) {
                $this->redirect(array('action' => 'index'));
            }
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Codeappellationromev3->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array('Codeappellationromev3.id' => $id)
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