<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php require_once( 'filtre.ctp' );?>
<?php
    if( isset( $cohortecui ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $xhtml->tag( 'p', $paginator->counter( $params ) );

    }
    else {
        $pagination = '';
    }
?>
<!-- Résultats -->
<?php if( isset( $cohortecui ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>
<?php echo $pagination;?>
    <?php if( is_array( $cohortecui ) && count( $cohortecui ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <?php echo $form->create( 'GestionContrat', array( 'url'=> Router::url( null, true ) ) );?>
        <?php
            echo '<div>';
            echo $form->input( 'Filtre.datecontrat', array( 'type' => 'hidden', 'id' => 'FiltreDatecontrat2' ) );
            echo $form->input( 'Filtre.datecontrat_from', array( 'type' => 'hidden', 'id' => 'FiltreDatecontratFrom2' ) );
            echo $form->input( 'Filtre.datecontrat_to', array( 'type' => 'hidden', 'id' => 'FiltreDatecontratTo2' ) );
            echo $form->input( 'Filtre.locaadr', array( 'type' => 'hidden', 'id' => 'FiltreLocaadr2' ) );
            echo $form->input( 'Filtre.numcomptt', array( 'type' => 'hidden', 'id' => 'FiltreNumcomptt2' ) );
            echo $form->input( 'Filtre.decisioncui', array( 'type' => 'hidden', 'id' => 'FiltreDecisioncui2' ) ); 
            echo $form->input( 'Filtre.datevalidationcui', array( 'type' => 'hidden', 'id' => 'FiltreDatevalidationcui2' )  );
            echo '</div>';
        ?>

            <table id="searchResults" class="tooltips">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Nom de l'allocataire</th>
                        <th>Commune de l'allocataire</th>
                        <th>Date du contrat</th>
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
                    <?php foreach( $cohortecui as $index => $contrat ):?>
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
                                h( date_short( $contrat['Cui']['datecontrat'] ) )
                            );

                            if( $this->action != 'nouveaux' ){
                                $array1[] = h( Set::enum( Set::extract( $contrat, 'Cui.decisioncui' ), $options['decisioncui'] ).' '.date_short( $contrat['Cui']['datevalidationcui'] ) );// statut BD
                            }

                            $array2 = array(
                                $form->input( 'Cui.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Cui']['id'] ) ).

                                $form->input( 'Cui.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Cui']['personne_id'] ) ).

                                    $form->input( 'Cui.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Dossier']['id'] ) ).
                                    $form->input( 'Cui.'.$index.'.decisioncui', array( 'label' => false, 'type' => 'select', 'options' => $options['decisioncui'], 'value' => $contrat['Cui']['proposition_decisioncui'] ) ),
                                h( date_short( $contrat['Cui']['proposition_datevalidationcui'] ) ).
                                    $form->input( 'Cui.'.$index.'.datevalidationcui', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Cui']['proposition_datevalidationcui'] ) ),

                                $form->input( 'Cui.'.$index.'.observcui', array( 'label' => false, 'type' => 'text', 'rows' => 2, 'value' => $contrat['Cui']['observcui'] ) ),

                                $xhtml->viewLink(
                                    'Voir le contrat « '.$title.' »',
                                    array( 'controller' => 'cuis', 'action' => 'view', $contrat['Cui']['id'] )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell' ) )
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

    <?php /*require( 'index.pagination.ctp' )*/ ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>