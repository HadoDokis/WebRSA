<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'courrierpdo', "Courrierspdos::{$this->action}", true )
    )
?>

<?php
    echo $default2->index(
        $courrierspdos,
        array(
            'Courrierpdo.name',
            'Courrierpdo.modeleodt'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'courrierspdos::edit',
                'courrierspdos::delete',
            ),
            'add' => 'courrierspdos::add'
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

