<?php
    echo $this->Xhtml->tag(
        'h1', $this->pageTitle = __d('codemetierromev3', "Codesmetiersromev3::{$this->action}")
    );
        
    echo $this->Default2->index(
        $codesmetiersromev3,
        array(
            'Codefamilleromev3.code',
            'Codedomaineproromev3.code',
            'Codemetierromev3.code',
            'Codemetierromev3.name'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Codesmetiersromev3::edit',
                'Codesmetiersromev3::delete' => array('disabled' => '\'#Codemetierromev3.occurences#\'!= "0"')
            ),
            'add' => 'Codemetierromev3::add'
        )
    );
    echo '<br />';
    
    echo $this->Default->button(
        'back',
        array(
            'controller' => 'codesromev3',
            'action' => 'index'
                ), array(
            'id' => 'Back'
        )
    );
?>