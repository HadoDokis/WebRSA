<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php require_once( 'filtre.ctp' );?>

<?php
    if( isset( $cohorteci ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>
<?php if( !empty( $this->data ) ):?>
    <?php if( empty( $cohorteci ) ):?>
        <?php
            switch( $this->action ) {
                case 'valides':
                    $message = 'Aucun contrat ne correspond à vos critères.';
                    break;
                default:
                    $message = 'Aucun contrat de validé n\'a été trouvé.';
            }
        ?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
        <?php echo $pagination;?>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom'.' '.'Personne.prenom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de début contrat', 'Contratinsertion.dd_ci' );?></th>
                    <th><?php echo $paginator->sort( 'Date de fin contrat', 'Contratinsertion.df_ci' );?></th>
                    <th><?php echo $paginator->sort( 'Décision', 'Contratinsertion.decision_ci' );?></th>
                    <th><?php echo $paginator->sort( 'Observations', 'Contratinsertion.observ_ci' );?></th>

                    <!-- <th>N° Dossier</th>
                    <th>Nom de l'allocataire</th>
                    <th>Commune de l'allocataire</th>
                    <th>Date début contrat</th>
                    <th>Date fin contrat</th>
                    <th>Décision</th>
                    <th>Observations</th> -->
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
                                h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) ),
                                h( $contrat['Contratinsertion']['observ_ci'] ),
                                $html->viewLink(
                                    'Voir le contrat',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'view', $contrat['Contratinsertion']['id'] ),
                                    $permissions->check( 'contratsinsertion', 'view' )
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
        <ul class="actionMenu">
            <!-- <li><?php
                echo $html->printCohorteLink(
                    'Imprimer la cohorte',
                    Set::merge(
                        array(
                            'controller' => 'gedooos',
                            'action'     => 'notifications_cohortes'
                        ),
                        array_unisize( $this->data )
                    )
                );
            ?></li>

            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    Set::merge(
                        array( 'controller' => 'cohortes', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                    )
                );
            ?></li>  -->
        </ul>
    <?php endif;?>
<?php endif;?>