<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $actionscandidats,
        array(
            'Actioncandidat.intitule',
            'Actioncandidat.code'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Actioncandidat.edit',
                'Actioncandidat.delete',
            ),
            'add' => 'Actioncandidat.add',
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'actionscandidats_personnes',
            'action'     => 'indexparams'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
