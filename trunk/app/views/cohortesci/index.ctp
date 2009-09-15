<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des contrats d\'insertion';?>

<h1>Gestion des Contrats d'insertion</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionDecisionCi', [ 'ContratinsertionDatevalidationCiDay', 'ContratinsertionDatevalidationCiMonth', 'ContratinsertionDatevalidationCiYear' ], 'V', false );
    });
</script>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDateSaisiCi', $( 'FiltreDateSaisiCiFromDay' ).up( 'fieldset' ), false );
    });
</script>


<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }
?>

<?php echo $form->create( 'Cohorteci', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche de Contrat d'insertion</legend>
            <?php echo $form->input( 'Filtre.date_saisi_ci', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du contrat</legend>
                <?php
                    $date_saisi_ci_from = Set::check( $this->data, 'Filtre.date_saisi_ci_from' ) ? Set::extract( $this->data, 'Filtre.date_saisi_ci_from' ) : strtotime( '-1 week' );
                    $date_saisi_ci_to = Set::check( $this->data, 'Filtre.date_saisi_ci_to' ) ? Set::extract( $this->data, 'Filtre.date_saisi_ci_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Filtre.date_saisi_ci_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_from ) );?>
                <?php echo $form->input( 'Filtre.date_saisi_ci_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_to ) );?>
            </fieldset>
            <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Filtre.pers_charg_suivi', array( 'label' => 'Contrat envoyé par ', 'type' => 'select' , 'options' => $personne_suivi, 'empty' => true ) );?>
            <?php echo $form->input( 'Filtre.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Filtre.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
            <?php echo $form->input( 'Filtre.forme_ci', array( 'label' => false, 'type' => 'radio', 'options' => array( 'S' => 'Simple', 'C' => 'Complexe' ), 'legend' => 'Forme du contrat', 'default' => 'S' ) ); ?>

    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $cohorteci ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $cohorteci ) && count( $cohorteci ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <?php echo $form->create( 'GestionContrat', array( 'url'=> Router::url( null, true ) ) );?>
            <table id="searchResults" class="tooltips_oupas">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Nom de l'allocataire</th>
                        <th>Commune de l'allocataire</th>
                        <th>Date début contrat</th>
                        <th>Date fin contrat</th>
                        <th>Statut actuel</th>
                        <th>Décision</th>
                        <th>Date validation</th>
                        <th>Observations</th>
                        <th class="action">Action</th>
                        <th class="innerTableHeader">Informations complémentaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $cohorteci as $index => $contrat ):?>
                        <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $contrat['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $contrat['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $contrat['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $contrat['Adresse']['codepos'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.h( $contrat['Adresse']['numcomptt'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                            $title = $contrat['Dossier']['numdemrsa'];

                            echo $html->tableCells(
                                array(
                                    h( $contrat['Dossier']['numdemrsa'] ),
                                    h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                    h( $contrat['Adresse']['locaadr'] ),
                                    h( date_short( $contrat['Contratinsertion']['dd_ci'] ) ),
                                    h( date_short( $contrat['Contratinsertion']['df_ci'] ) ),
                                    h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) ),// statut BD
                                    $form->input( 'Contratinsertion.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['id'] ) ).
                                        $form->input( 'Contratinsertion.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Dossier']['id'] ) ).
                                        $form->input( 'Contratinsertion.'.$index.'.decision_ci', array( 'label' => false, 'type' => 'select', 'options' => $decision_ci, 'value' => $contrat['Contratinsertion']['proposition_decision_ci'] ) ),
                                    h( date_short( $contrat['Contratinsertion']['proposition_datevalidation_ci'] ) ).
                                     $form->input( 'Contratinsertion.'.$index.'.datevalidation_ci', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['proposition_datevalidation_ci'] ) ),
                                    $form->input( 'Contratinsertion.'.$index.'.observ_ci', array( 'label' => false, 'type' => 'text', 'rows' => 2, 'value' => $contrat['Contratinsertion']['observ_ci'] ) ),
                                    $html->viewLink(
                                        'Voir le contrat « '.$title.' »',
                                        array( 'controller' => 'contratsinsertion', 'action' => 'view', $contrat['Contratinsertion']['id'] )
                                    ),
                                    array( $innerTable, array( 'class' => 'innerTableCell' ) )
                                ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                            );
                        ?>
                    <?php endforeach;?>
                </tbody>
            </table>

            <?php echo $form->submit( 'Validation de la liste' );?>
        <?php echo $form->end();?>

    <?php /*require( 'index.pagination.ctp' )*/ ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>