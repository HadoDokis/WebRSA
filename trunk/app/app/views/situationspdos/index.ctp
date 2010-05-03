<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $situationspdos,
        array(
            'Situationpdo.libelle'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Situationpdo.edit',
                'Situationpdo.delete',
            ),
            'add' => 'Situationpdo.add',
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
