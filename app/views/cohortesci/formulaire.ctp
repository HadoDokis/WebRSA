<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php require_once( 'filtre.ctp' );?>
<?php
    if( isset( $cohorteci ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

//         $pages = $paginator->first( '<<' );
//         $pages .= $paginator->prev( '<' );
//         $pages .= $paginator->numbers();
//         $pages .= $paginator->next( '>' );
//         $pages .= $paginator->last( '>>' );
// 
//         $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>
<!-- Résultats -->
<?php if( isset( $cohorteci ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>
<?php echo $pagination;?>
    <?php if( is_array( $cohorteci ) && count( $cohorteci ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <?php echo $form->create( 'GestionContrat', array( 'url'=> Router::url( null, true ) ) );?>
        <?php
            echo '<div>';
            echo $form->input( 'Filtre.date_saisi_ci', array( 'type' => 'hidden', 'id' => 'FiltreDateSaisiCi2' ) );
            echo $form->input( 'Filtre.date_saisi_ci_from', array( 'type' => 'hidden', 'id' => 'FiltreDateSaisiCiFrom2' ) );
            echo $form->input( 'Filtre.date_saisi_ci_to', array( 'type' => 'hidden', 'id' => 'FiltreDateSaisiCiTo2' ) );
            echo $form->input( 'Filtre.locaadr', array( 'type' => 'hidden', 'id' => 'FiltreLocaadr2' ) );
            echo $form->input( 'Filtre.numcomptt', array( 'type' => 'hidden', 'id' => 'FiltreNumcomptt2' ) );
            echo $form->input( 'Filtre.pers_charg_suivi', array( 'type' => 'hidden', 'id' => 'FiltrePersChargSuivi2' ) );
            echo $form->input( 'Filtre.decision_ci', array( 'type' => 'hidden', 'id' => 'FiltreDecisionCi2' ) ); 
            echo $form->input( 'Filtre.datevalidation_ci', array( 'type' => 'hidden', 'id' => 'FiltreDatevalidationCi2' )  );
            echo $form->input( 'Filtre.forme_ci', array( 'type' => 'hidden', 'id' => 'FiltreFormeCi2' ) );
            echo '</div>';
        ?>

            <table id="searchResults" class="tooltips">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Nom de l'allocataire</th>
                        <th>Commune de l'allocataire</th>
                        <th>Date début contrat</th>
                        <th>Date fin contrat</th>
                        <?php if( $this->action != 'nouveaux' ):?>
                            <th>Statut actuel</th>
                        <?php endif;?>
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

                            $array1 = array(
                                h( $contrat['Dossier']['numdemrsa'] ),
                                h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                h( $contrat['Adresse']['locaadr'] ),
                                h( date_short( $contrat['Contratinsertion']['dd_ci'] ) ),
                                h( date_short( $contrat['Contratinsertion']['df_ci'] ) )
                            );

                            if( $this->action != 'nouveaux' ){
                                $array1[] = h( Set::extract( $decision_ci, Set::extract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) );// statut BD
                            }

                            $array2 = array(
                                $form->input( 'Contratinsertion.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['id'] ) ).

                                $form->input( 'Contratinsertion.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['personne_id'] ) ).

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
                            );

                            echo $html->tableCells(
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

    <?php /*require( 'index.pagination.ctp' )*/ ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>