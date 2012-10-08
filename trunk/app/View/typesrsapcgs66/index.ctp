<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'typersapcg66', "Typesrsapcgs66::{$this->action}", true )
    );
?>
<?php
    echo $default2->index(
        $typesrsapcgs66,
        array(
            'Typersapcg66.name'
        ),
        array(
            'actions' => array(
                'Typesrsapcgs66::edit',
                'Typesrsapcgs66::delete'
            ),
            'add' => 'Typesrsapcgs66::add'
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'pdos',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>