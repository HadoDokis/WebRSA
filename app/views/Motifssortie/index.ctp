<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'motifsortie', "Motifssortie::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $motifssortie,
        array(
            'Motifsortie.name',
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Motifsortie.edit',
                'Motifsortie.delete',
            ),
            'add' => 'Motifsortie.add'
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
