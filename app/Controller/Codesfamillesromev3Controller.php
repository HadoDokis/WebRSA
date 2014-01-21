<?php

/**
 * Code source de la classe Codesfamillesromev3Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
//    App::import( 'Behaviors', 'Occurences' );
/**
 * La classe Codesfamillesromev3Controller ...
 *
 * @package app.Controller
 */
class Codesfamillesromev3Controller extends AppController {

    public $name = 'Codesfamillesromev3';
    public $uses = array('Codefamilleromev3', 'Option');
    public $helpers = array('Xform', 'Default', 'Default2', 'Theme');
    public $components = array('Default');
    public $commeDroit = array(
        'view' => 'Codesfamillesromev3:index',
        'add' => 'Codesfamillesromev3:edit'
    );

    /**
     *   Ajout à la suite de l'utilisation des nouveaux helpers
     *   - default.php
     *   - theme.php
     */
    public function index() {

        $this->Codefamilleromev3->Behaviors->attach('Occurences');
        $querydata = $this->Codefamilleromev3->qdOccurencesExists(
            array(
                'fields' => array_merge(
                    $this->Codefamilleromev3->fields()
                ),
                'contain' => false,
                'order' => array('Codefamilleromev3.name ASC')
            )
        );
        $this->paginate = $querydata;
        $codesfamillesromev3 = $this->paginate('Codefamilleromev3');

        $this->set(compact('codesfamillesromev3'));
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
            $this->redirect(array('controller' => 'codesfamillesromev3', 'action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->Codefamilleromev3->create($this->request->data);
            $success = $this->Codefamilleromev3->save();

            $this->_setFlashResult('Save', $success);
            if ($success) {
                $this->redirect(array('action' => 'index'));
            }
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Codefamilleromev3->find(
                'first',
                array(
                    'contain' => false,
                    'conditions' => array('Codefamilleromev3.id' => $id)
                )
            );
            $this->assert(!empty($this->request->data), 'error404');
        }

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