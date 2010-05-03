<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId) ); ?>

<div class="with_treemenu">

    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'actioncandidat_personne', "ActionscandidatsPersonnes::{$this->action}", true )
        )
    ?>

    <?php
        echo $default->index(
            $actionscandidats_personnes,
            array(
                'Actioncandidat.intitule',
                'Referent.nom_complet',
                'Actioncandidat.Partenaire.0.libstruc',
                'ActioncandidatPersonne.ddaction',
                'ActioncandidatPersonne.dfaction'
            ),
            array(
                'cohorte' => false,
                'actions' => array(
                    'ActioncandidatPersonne.edit',
                    'ActioncandidatPersonne.gedooo'
                ),
                'add' => array( 'ActioncandidatPersonne.add' => $this->params['pass'][0] ),
            )
        );
//     debug($actionscandidats_personnes);
    ?>
</div>
<div class="clearer"><hr /></div>