<?php
    echo $this->Xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'modeletypecourrierpcg66', "Modelestypescourrierspcgs66::{$this->action}" )
    );
?>
<?php
    echo $this->Default2->index(
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