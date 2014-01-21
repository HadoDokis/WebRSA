<?php
    echo $this->Xhtml->tag(
        'h1', $this->pageTitle = __d('codeappellationromev3', "Codesappellationsromev3::{$this->action}")
    );
        
    echo $this->Default2->index(
        $codesappellationsromev3,
        array(
            'Codefamilleromev3.code',
            'Codedomaineproromev3.code',
            'Codemetierromev3.code',
            'Codemetierromev3.name',
            'Codeappellationromev3.name'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Codesappellationsromev3::edit',
                'Codesappellationsromev3::delete' => array('disabled' => '\'#Codeappellationromev3.occurences#\'!= "0"')
            ),
            'add' => 'Codeappellationromev3::add'
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