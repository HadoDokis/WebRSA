<?php
    echo $this->Xhtml->tag(
        'h1', $this->pageTitle = __d('codedomaineproromev3', "Codesdomainesprosromev3::{$this->action}")
    );
        
    echo $this->Default2->index(
        $codesdomainesprosromev3,
        array(
            'Codefamilleromev3.code',
            'Codedomaineproromev3.code',
            'Codedomaineproromev3.name'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Codesdomainesprosromev3::edit',
                'Codesdomainesprosromev3::delete' => array('disabled' => '\'#Codedomaineproromev3.occurences#\'!= "0"')
            ),
            'add' => 'Codedomaineproromev3::add'
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