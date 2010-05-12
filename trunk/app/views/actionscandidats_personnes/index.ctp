<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId) );
?>

<div class="with_treemenu">

    <?php

        echo $html->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
        )
    ?>

    <?php
        echo $default->index(
            $actionscandidats_personnes,
            array(
                'Actioncandidat.intitule',
                'Referent.nom_complet',
                'Actioncandidat.Partenaire.0.libstruc',
                'ActioncandidatPersonne.datesignature' => array( 'domain' => $domain )/*,
                'ActioncandidatPersonne.ddaction',
                'ActioncandidatPersonne.dfaction'*/
            ),
            array(
//                 'cohorte' => false,
                'actions' => array(
                    'ActioncandidatPersonne.edit' => array( 'domain' => $domain ),
                    'ActioncandidatPersonne.gedooo' => array( 'domain' => $domain )
                ),
                'add' => array( 'ActioncandidatPersonne.add' => $this->params['pass'][0] )
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>