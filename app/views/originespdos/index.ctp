<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'originepdo', "Originespdos::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $originespdos,
        array(
            'Originepdo.libelle'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Originepdo.edit',
                'Originepdo.delete',
            ),
            'add' => 'Originepdo.add',
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
