<?php
    echo $this->Xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'typecourrierpcg66', "Typescourrierspcgs66::{$this->action}" )
    );
?>
<?php
    echo $this->Default2->index(
        $typescourrierspcgs66,
        array(
            'Typecourrierpcg66.name'
        ),
        array(
            'actions' => array(
                'Typescourrierspcgs66::edit',
                'Typescourrierspcgs66::delete'
            ),
            'add' => 'Typescourrierspcgs66::add'
        )
    );

    echo $this->Default->button(
        'back',
        array(
            'controller' => 'courrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>