<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par contrats d\'insertion';?>

<h1>Recherche par Contrat d'insertion</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionDecisionCi', [ 'ContratinsertionDatevalidationCiDay', 'ContratinsertionDatevalidationCiMonth', 'ContratinsertionDatevalidationCiYear' ], 'V', false );
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

<?php echo $form->create( 'Critereci', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par Contrat d'insertion</legend>
            <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => 'Date de saisie du contrat', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => date( 'Y' ) - 10, 'empty' => true ) );?>
            <?php echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Serviceinstructeur.id', array( 'label' => 'Contrat envoyé par ', 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
            <?php echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>

    </fieldset>

    <div class="submit">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $contrats ) ):?>
    <h2>Résultats de la recherche</h2>

    <?php if( is_array( $contrats ) && count( $contrats ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom allocataire</th>
                    <th>Commune de l'allocataire</th>
                    <th>Contrat envoyé par</th>
                    <th>N° CAF</th>
                    <th>Date de saisie du contrat</th>
                    <th>Durée (en mois)</th>
                    <th>Décision</th>
                    <th class="action">Actions</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $contrats as $index => $contrat ):?>
                    <?php
                        $title = $contrat['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                               <!-- <tr>
                                    <th>Commune de naissance</th>
                                    <td>'.$contrat['Personne']['nomcomnai'].'</td>
                                </tr> -->
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $contrat['Personne']['dtnai'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        echo $html->tableCells(
                            array(
                                h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                h( $contrat['Adresse']['locaadr'] ),
                                h( $contrat['Contratinsertion']['pers_charg_suivi'] ),
                                h( $contrat['Dossier']['matricule'] ),
                                h( $contrat['Contratinsertion']['date_saisi_ci'] ),
                                h( $contrat['Contratinsertion']['duree_engag'] ),
                                h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.$contrat['Contratinsertion']['datevalidation_ci']),
                                $html->viewLink(
                                    'Voir le dossier « '.$title.' »',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'index', $contrat['Contratinsertion']['personne_id'] )
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
<!--        <div class="submit">
        <?php echo $form->button( 'Validation de la liste', array( 'type' => 'submit' ) );?>
        </div>-->
        <?php /*require( 'index.pagination.ctp' )*/;?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>