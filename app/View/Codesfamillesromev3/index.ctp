<?php
    echo $this->Xhtml->tag(
        'h1', $this->pageTitle = __d('codefamilleromev3', "Codesfamillesromev3::{$this->action}")
    );
        
    echo $this->Default2->index(
        $codesfamillesromev3,
        array(
            'Codefamilleromev3.code',
            'Codefamilleromev3.name'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Codesfamillesromev3::edit',
                'Codesfamillesromev3::delete' => array('disabled' => '\'#Codefamilleromev3.occurences#\'!= "0"')
            ),
            'add' => 'Codefamilleromev3::add'
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