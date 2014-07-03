<?php
    echo $this->Xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'piecemailcui66', "Piecesmailscuis66::{$this->action}" )
    );
?>
<?php

    
    echo $this->Default2->index(
        $piecesmailscuis66,
        array(
            'Piecemailcui66.name'
        ),
        array(
            'actions' => array(
                'Piecesmailscuis66::edit',
                'Piecesmailscuis66::delete' => array( 'disabled' => '\'#Piecemailcui66.occurences#\'!= "0"' )
            ),
            'add' => 'Piecesmailscuis66::add'
        )
    );
    echo $this->Default->button(
        'back',
        array(
            'controller' => 'cuis',
            'action'     => 'indexparams'
        ),
        array(
            'id' => 'Back'
        )
    );
?>