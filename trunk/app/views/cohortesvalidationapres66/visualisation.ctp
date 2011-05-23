<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'APREs validées';
?>

<h1><?php echo $this->pageTitle;?></h1>

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

<?php echo $xform->create( 'Cohortevalidationapre66', array( 'type' => 'post', 'action' => $this->action,  'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

        <fieldset>
            <?php echo $xform->input( 'Cohortevalidationapre66.validees', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <legend>Filtrer par APRE</legend>
            <?php

                echo $default2->subform(
                    array(
                        'Apre.numeroapre' => array( 'label' => __d( 'apre', 'Apre.numeroapre', true ), 'type' => 'text' ),
                        'Apre.referent_id' => array( 'label' => __d( 'apre', 'Apre.referent_id', true ), 'options' => $referents ),
                        'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                        'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                        'Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
                        'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),

                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>


<?php if( isset( $cohortevalidationapre66 ) ):?>
    <?php if( empty( $cohortevalidationapre66 ) ):?>
        <?php
            switch( $this->action ) {
                case 'validees':
                    $message = 'Aucune APRE ne correspond à vos critères.';
                    break;
                default:
                    $message = 'Aucune APRE de validée n\'a été trouvée.';
            }
        ?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
<?php $pagination = $xpaginator->paginationBlock( 'Apre', $this->passedArgs ); ?>
<?php echo $pagination;?>
    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th>N° Demande APRE</th>
                <th>Nom de l'allocataire</th>
                <th>Commune de l'allocataire</th>
                <th>Date demande APRE</th>
                <th>Etat du dossier</th>
                <th>Décision</th>
                <th>Montant accordé</th>
                <th>Motif du rejet</th>
                <th>Date de la décision</th>
                <th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortevalidationapre66 as $index => $validationapre ):?>
            <?php
                    $innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $validationapre['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>Numéro CAF</th>
                                <td>'.h( $validationapre['Dossier']['matricule'] ).'</td>
                            </tr>
                            <tr>
                                <th>NIR</th>
                                <td>'.h( $validationapre['Personne']['nir'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code postal</th>
                                <td>'.h( $validationapre['Adresse']['codepos'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code INSEE</th>
                                <td>'.h( $validationapre['Adresse']['numcomptt'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';
                    $title = $validationapre['Dossier']['numdemrsa'];

                    echo $xhtml->tableCells(
                            array(
                                h( $validationapre['Apre']['numeroapre'] ),
                                h( $validationapre['Personne']['qual'].' '.$validationapre['Personne']['nom'].' '.$validationapre['Personne']['prenom'] ),
                                h( $validationapre['Adresse']['locaadr'] ),
                                h( date_short(  $validationapre['Aideapre66']['datedemande'] ) ),
                                h( Set::enum( Set::classicExtract( $validationapre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ) ),
                                h( Set::enum( Set::classicExtract( $validationapre, 'Aideapre66.decisionapre' ), $optionsaideapre66['decisionapre'] ) ),
                                h( (  $validationapre['Aideapre66']['montantaccorde'] ) ),
                                h( $validationapre['Aideapre66']['motifrejetequipe'] ),
                                h( date_short(  $validationapre['Aideapre66']['datemontantaccorde'] ) ),
                                $xhtml->viewLink(
                                    'Voir le contrat',
                                    array( 'controller' => 'apres66', 'action' => 'index', $validationapre['Personne']['id'] ),
                                    $permissions->check( 'apres66', 'index' )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell' ) ),
                            ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                        );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php echo $pagination;?>

<?php endif?>
<?php endif?>