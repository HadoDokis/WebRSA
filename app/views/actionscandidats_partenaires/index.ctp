<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat_partenaire', "ActionscandidatsPartenaires::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $actionscandidats_partenaires,
        array(
            'Actioncandidat.intitule',
            'Partenaire.libstruc',
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'ActioncandidatPartenaire.edit',
                'ActioncandidatPartenaire.delete',
            ),
            'add' => 'ActioncandidatPartenaire.add',
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
