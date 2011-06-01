<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'contactpartenaire', "Contactspartenaires::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $contactspartenaires,
        array(
            'Contactpartenaire.qual' => array( 'options' => $qual ),
            'Contactpartenaire.nom',
            'Contactpartenaire.prenom',
            'Contactpartenaire.numtel',
            'Contactpartenaire.numfax',
            'Contactpartenaire.email',
            'Partenaire.libstruc'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Contactpartenaire.edit',
                'Contactpartenaire.delete',
            ),
            'add' => 'Contactpartenaire.add',
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
