<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
    )
?>

<?php
    echo $default->index(
        $actionscandidats,
        array(
            'Actioncandidat.name',
            'Actioncandidat.codeaction' => array('type'=>'text'),
        	'Actioncandidat.lieuaction',
        	'Actioncandidat.cantonaction',
        	'Actioncandidat.ddaction',
        	'Actioncandidat.dfaction',
        	'Actioncandidat.nbpostedispo',
        	'Actioncandidat.hasfichecandidature',
        	'Contactpartenaire.Partenaire.libstruc',
        	'Contactpartenaire.nom_candidat'
        ),
        array(
            'cohorte' => false,
            'actions' => array(
                'Actioncandidat.edit',
                'Actioncandidat.delete',
            ),
            'add' => 'Actioncandidat.add',
            'options' => $options
        )
    );
// debug($actionscandidats);
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
