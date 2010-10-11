<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'statutdecisionpdo', "Statutsdecisionspdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $statutsdecisionspdos,
        array(
            'Statutdecisionpdo.libelle'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Statutdecisionpdo.edit',
                'Statutdecisionpdo.delete',
            ),
            'add' => 'Statutdecisionpdo.add',
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
