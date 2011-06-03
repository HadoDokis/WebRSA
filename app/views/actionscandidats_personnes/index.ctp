<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">

    <?php

        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
        );
    ?>

    <?php
//     debug($actionscandidats_personnes);
        echo $default2->index(
            $actionscandidats_personnes,
            array(
                'Actioncandidat.name',
                'Referent.nom_complet' => array( 'type' => 'text' ),
                'Actioncandidat.Contactpartenaire.Partenaire.libstruc',
                'ActioncandidatPersonne.datesignature' => array( 'domain' => $domain ),
                'ActioncandidatPersonne.positionfiche'
            ),
            array(
                'options' => $options,
                'actions' => array(
//                     'ActioncandidatPersonne.view' => array( 'domain' => $domain ),
                    'ActionscandidatsPersonnes::edit' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'edit' ).'" != "1" )  || ( "#ActioncandidatPersonne.positionfiche#" == "annule" )' ),
                    'ActionscandidatsPersonnes::cancel' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'cancel' ).'" != "1" ) '  ),
                    'ActionscandidatsPersonnes::printFiche' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'printFiche' ).'" != "1" )  || ( "#ActioncandidatPersonne.positionfiche#" == "annule" ) '  )
                ),
                'add' => array( 'ActioncandidatPersonne.add' => array( 'controller'=>'actionscandidats_personnes', 'action'=>'add', $personne_id ) )
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>