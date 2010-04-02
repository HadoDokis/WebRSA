<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $partenaires,
        array(
            'Partenaire.libstruc',
            'Partenaire.numvoie',
            'Partenaire.typevoie',
            'Partenaire.nomvoie',
            'Partenaire.compladr',
            'Partenaire.numtel',
            'Partenaire.numfax',
            'Partenaire.email',

            'Partenaire.codepostal',
            'Partenaire.ville'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Partenaire.edit',
                'Partenaire.delete',
            ),
            'add' => 'Partenaire.add',
            'options' => $options
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
