<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par critères';?>

<h1>Recherche par critères</h1>

<ul class="actionMenu">
    <?php
        if( $session->read( 'Auth.User.username' ) == 'cg66' ) { // FIXME
            echo '<li>'.$html->addSimpleLink(
                'Ajouter une préconisation d\'orientation',
                array( 'controller' => 'dossierssimplifies', 'action' => 'add' )
            ).' </li>';
        }

        if( is_array( $this->data ) ) {
            echo '<li>'.$html->link(
                $html->image(
                    'icons/application_form_magnify.png',
                    array( 'alt' => '' )
                ).' Formulaire',
                '#',
                array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
            ).'</li>';
        }
    ?>
</ul>
<?php echo $form->create( 'Critere', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<!--  
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
        });
    </script>

    <fieldset>
        <legend>Recherche par dossier</legend>
        <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => strtotime( '-1 week' ) ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
        </fieldset>
-->
    </fieldset>
    <fieldset>
        <legend>Recherche par types d'orientations</legend>
        <?php echo $form->input( 'Typeorient.id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select' , 'options' => $typeorient, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par Structures référentes</legend>
            <?php echo $form->input( 'Structurereferente.id', array( 'label' => 'Nom de la structure', 'type' => 'select' , 'options' => $typestruct, 'empty' => true  ) );?>
    </fieldset>
<!--  
    <fieldset>
        <legend>Recherche par adresse </legend>
            <?php echo $form->input( 'Adresse.id', array( 'label' =>  __( 'locaadr', true ), 'type' => 'select' , 'options' => $communesAlloc, 'empty' => true  ) );?>
    </fieldset>
-->
    <fieldset>
        <legend>Recherche par Statut</legend>
        <?php echo $form->input( 'Orientstructs.statut_orient', array( 'label' => 'Statut de l\'orientation', 'type' => 'select', 'options' => $statuts, 'empty' => true ) );?>
    </fieldset>
    
    <fieldset>
        <legend>Recherche par Contrat d'insertion</legend>
        <?php echo $form->input( 'Contratsinsertions.ddci', array( 'label' => 'Date de début du contrat ', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
        <?php echo $form->input( 'Contratsinsertions.statut', array( 'label' => "Statut du contrat d'insertion", 'type' => 'select', 'options' => $statuts_contrat, 'empty' => true ) );?>
        <?php echo $form->input( 'Servicesinstructeurs.lib_service', array( 'label' => 'Service instructeur', 'type' => 'select', 'options' => $services_instructeur, 'empty' => true ) );?>
        <?php echo $form->input( 'Servicesinstructeurs.lib_service', array( 'label' => 'Envoyé par', 'type' => 'select', 'options' => $services_instructeur, 'empty' => true ) );?>



    </fieldset>


    <?php echo $form->submit( 'Rechercher' );?>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $criteres ) ):?>
    <h2>Résultats de la recherche</h2>

    <?php if( is_array( $criteres ) && count( $criteres ) > 0 ):?>
        <?php //require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom/Prénom</th>
                    <th>Commune</th>
                    <th>Contrat envoyé par</th>
                    <th>N° CAF</th>
                    <th>Date début du contrat</th>
                    <th>Durée</th>
                    <th>Décision</th>
                    <th class="action">Actions</th>
                   <!-- <th class="innerTableHeader">Informations complémentaires</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach( $criteres as $index => $critere ):?>
                    <?php 
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Commune de naissance</th>
                                    <td>'. $critere[0]['nomcomnai'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $critere[0]['dtnai']).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $critere[0]['nom'].' '.$critere[0]['prenom'] ),
                                h( isset( $critere['Dossier']['Foyer']['id'] ) ? $critere['Dossier']['Foyer']['id'] : null),
                                h( isset( $critere['Contratinsertion']['statut'] ) ? $critere['Contratinsertion']['statut'] : null ),
                                h( isset( $critere['Dossier']['Dossier']['matricule'] ) ? $critere['Dossier']['Dossier']['matricule'] : null),
                                h( date_short( isset( $critere['Contratinsertion']['dd_ci'] ) ) ? date_short( $critere['Contratinsertion']['dd_ci'] ) : null ), // FIXME: 0
                                h( isset( $critere['Contratinsertion']['decision_ci'] ) ? $critere['Contratinsertion']['decision_ci'] : null ), // FIXME: 0
//                                 implode(
//                                     ' ',
//                                     array(
//                                         $critere[0]['qual'],
//                                         $critere[0]['nom'],
//                                         implode( ' ', array( $critere[0]['prenom'], $critere[0]['prenom2'], $critere[0]['prenom3'] ) )
//                                     )
//                                 ),
                                h(' '),
                                $html->viewLink(
                                    'Voir le dossier « '.$critere['Dossier']['Dossier']['numdemrsa'].' »',
                                    array( 'controller' => 'personnes', 'action' => 'view', $critere[0]['id'] )
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

        <?php //require( 'index.pagination.ctp' )?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>
