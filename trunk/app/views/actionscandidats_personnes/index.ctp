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
                'Actioncandidat.Partenaire.0.libstruc',
                'ActioncandidatPersonne.datesignature' => array( 'domain' => $domain ),
                'ActioncandidatPersonne.positionfiche'
            ),
            array(
                'options' => $options,
                'actions' => array(
//                     'ActioncandidatPersonne.view' => array( 'domain' => $domain ),
                    'ActionscandidatsPersonnes::edit' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'edit' ).'" != "1" ) ' ),
                    'ActionscandidatsPersonnes::delete' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'delete' ).'" != "1" ) '  ),
                    'ActionscandidatsPersonnes::gedooo' => array( 'domain' => $domain, 'disabled' =>  '( "'.$permissions->check( 'actionscandidats_personnes', 'gedooo' ).'" != "1" ) '  )
                ),
                'add' => array( 'ActioncandidatPersonne.add' => array( 'controller'=>'actionscandidats_personnes', 'action'=>'add', $personne_id ) )
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>