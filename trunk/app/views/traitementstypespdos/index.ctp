<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'traitementtypepdo', "Traitementstypespdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $traitementstypespdos,
        array(
            'Traitementtypepdo.name'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Traitementtypepdo.edit',
                'Traitementtypepdo.delete',
            ),
            'add' => 'Traitementtypepdo.add',
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
