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
        echo $default->index(
            $actionscandidats_personnes,
            array(
                'Actioncandidat.name',
                'Referent.nom_complet',
                'Actioncandidat.Partenaire.0.libstruc',
                'ActioncandidatPersonne.datesignature' => array( 'domain' => $domain )/*,
                'ActioncandidatPersonne.ddaction',
                'ActioncandidatPersonne.dfaction'*/
            ),
            array(
//                 'cohorte' => false,
                'actions' => array(
                    'ActioncandidatPersonne.view' => array( 'domain' => $domain ),            
                    'ActioncandidatPersonne.edit' => array( 'domain' => $domain ),
                    'ActioncandidatPersonne.delete' => array( 'domain' => $domain ),
                    'ActioncandidatPersonne.gedooo' => array( 'domain' => $domain )
                ),
                'add' => array( 'ActioncandidatPersonne.add' => $this->params['pass'][0] )
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>