<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'modeletypecourrierpcg66', "Modelestypescourrierspcgs66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
        $modelestypescourrierspcgs66,
        array(
            'Modeletypecourrierpcg66.name',
            'Typecourrierpcg66.name',
            'Modeletypecourrierpcg66.modeleodt'
        ),
        array(
            'actions' => array(
                'Modelestypescourrierspcgs66::edit',
                'Modelestypescourrierspcgs66::delete'
            ),
            'add' => 'Modelestypescourrierspcgs66::add'
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