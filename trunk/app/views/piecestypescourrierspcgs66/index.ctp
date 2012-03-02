<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'piecetypecourrierpcg66', "Piecestypescourrierspcgs66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
        $piecestypescourrierspcgs66,
        array(
            'Piecetypecourrierpcg66.name',
            'Typecourrierpcg66.name'
        ),
        array(
            'actions' => array(
                'Piecestypescourrierspcgs66::edit',
                'Piecestypescourrierspcgs66::delete'
            ),
            'add' => 'Piecestypescourrierspcgs66::add'
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