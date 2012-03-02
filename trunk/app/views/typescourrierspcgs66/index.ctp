<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'typecourrierpcg66', "Typescourrierspcgs66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
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

    echo $default->button(
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