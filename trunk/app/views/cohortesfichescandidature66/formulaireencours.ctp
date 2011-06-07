<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'ActioncandidatDatesignature', $( 'ActioncandidatDatesignatureFromDay' ).up( 'fieldset' ), false );
    });
</script>
<?php
    echo '<ul class="actionMenu"><li>'.$xhtml->link(
        $xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>

<?php echo $xform->create( 'Cohortefichecandidature66', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

    <fieldset>
            <?php echo $xform->input( 'Actioncandidat.indexparams', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <legend>Filtrer par Fiche de candidature</legend>
            <?php

                echo $default2->subform(
                    array(
                        'Partenaire.codepartenaire' => array( 'type' => 'text'),
                        'Partenaire.libstruc' => array( 'type' => 'select', 'options' => $partenaires ),
                        'Actioncandidat.name' => array( 'type' => 'select', 'options' => $actions ),
                        'Actioncandidat.referent_id' => array( 'type' => 'select', 'options' => $referents ),
                        'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                        'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                        'Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
                        'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'ActioncandidatPersonne.referent_id' => array( 'type' => 'select', 'options' => $referents ),
                        'ActioncandidatPersonne.positionfiche' => array( 'type' => 'select', 'options' => $options['positionfiche'] ),

                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

            <?php echo $xform->input( 'Actioncandidat.datesignature', array( 'label' => 'Filtrer par date de Fiche de candidature', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Filtrer par période</legend>
                <?php
                    $datesignature_from = Set::check( $this->data, 'Actioncandidat.datesignature_from' ) ? Set::extract( $this->data, 'Actioncandidat.datesignature_from' ) : strtotime( '-1 week' );
                    $datesignature_to = Set::check( $this->data, 'Actioncandidat.datesignature_to' ) ? Set::extract( $this->data, 'Actioncandidat.datesignature_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Actioncandidat.datesignature_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_from ) );?>
                <?php echo $xform->input( 'Actioncandidat.datesignature_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_to ) );?>
            </fieldset>
    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'ActioncandidatPersonne', $this->passedArgs ); ?>
<?php echo $pagination;?>
<?php if( isset( $cohortefichecandidature66 ) ):?>
    <?php if( is_array( $cohortefichecandidature66 ) && count( $cohortefichecandidature66 ) > 0  ):?>
        <?php echo $form->create( 'SuiviActioncandidatPersonne', array( 'url'=> Router::url( null, true ) ) );?>

    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th>N° Dossier</th>
                <th>Nom de l'allocataire</th>
                <th>Commune de l'allocataire</th>
                <th>Action engagée</th>
                <th>Partenaire lié</th>
                <th>Nom du prescripteur</th>
                <th>Date de signature</th>
                <th>Venu(e) ?</th>
                <th>Retenu(e) ?</th>
                <th>Motif de sortie</th>
                <th>Date de sortie</th>
                <th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortefichecandidature66 as $index => $ficheenattente ):?>
            <?php
/*debug($ficheenattente);
debug($options);*/
                    $title = $ficheenattente['Dossier']['numdemrsa'];

                    $array1 = array(
                        h( $ficheenattente['Dossier']['numdemrsa'] ),
                        h( $ficheenattente['Personne']['qual'].' '.$ficheenattente['Personne']['nom'].' '.$ficheenattente['Personne']['prenom'] ),
                        h( $ficheenattente['Adresse']['locaadr'] ),
                        h( $ficheenattente['Actioncandidat']['name'] ),
                        h( $ficheenattente['Partenaire']['libstruc'] ),
                        h( $ficheenattente['Referent']['qual'].' '.$ficheenattente['Referent']['nom'].' '.$ficheenattente['Referent']['prenom'] ),
                        h( date_short( $ficheenattente['ActioncandidatPersonne']['datesignature'] ) ),
                        h( Set::enum( $ficheenattente['ActioncandidatPersonne']['bilanvenu'], $options['bilanvenu'] ) ),
                        h( Set::enum( $ficheenattente['ActioncandidatPersonne']['bilanretenu'], $options['bilanretenu'] ) ),
                    );

                    $array2 = array(
                        $form->input( 'ActioncandidatPersonne.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['id'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['personne_id'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.actioncandidat_id', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['actioncandidat_id'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.referent_id', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['referent_id'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.bilanvenu', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['bilanvenu'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.bilanretenu', array( 'label' => false, 'type' => 'hidden', 'value' => $ficheenattente['ActioncandidatPersonne']['bilanretenu'] ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.issortie', array( 'label' => false, 'type' => 'hidden', 'value' => 1 ) ).
                        $form->input( 'ActioncandidatPersonne.'.$index.'.motifsortie_id', array( 'label' => false, 'empty' => true,  'type' => 'select', 'options' => $motifssortie, 'selected' => $ficheenattente['ActioncandidatPersonne']['motifsortie_id'] ) ),

                        $form->input( 'ActioncandidatPersonne.'.$index.'.sortiele', array( 'label' => false, /*'empty' => true,*/  'type' => 'date', 'dateFormat' => 'DMY', 'selected' => $ficheenattente['ActioncandidatPersonne']['proposition_sortiele'] ) ),
                        $xhtml->viewLink(
                            'Voir le contrat « '.$title.' »',
                            array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $ficheenattente['ActioncandidatPersonne']['personne_id'] )
                        )
                    );

                    echo $xhtml->tableCells(
                        Set::merge( $array1, $array2 ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php echo $pagination;?>
    <?php echo $form->submit( 'Validation de la liste' );?>
<?php echo $form->end();?>


    <?php else:?>
        <p class="notice">Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>