<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'statutpdo', "Statutspdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $statutspdos,
        array(
            'Statutpdo.libelle'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Statutpdo.edit',
                'Statutpdo.delete',
            ),
            'add' => 'Statutpdo.add',
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
