<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Avis commission APREs';?>

<h1>Avis commission APREs</h1>

<?php

    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
    //

    if( isset( $commissionapre ) ) {
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

<?php require_once( 'filtre.ctp' );?>

<!-- Résultats -->

<?php if( isset( $commissionapre ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $commissionapre ) && count( $commissionapre ) > 0 ):?>
        <?php echo $form->create( 'GestionPDO', array( 'url'=> Router::url( null, true ) ) );?>
    <?php echo $pagination;?> 
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'N° CAF/MSA', 'Dossier.matricule' );?></th>
                    <th><?php echo $paginator->sort( 'Ville', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de la demande RSA', 'Dossier.dtdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Type de PDO', 'Propopdo.typepdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Décision PDO', 'Propopdo.decisionpdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Date de décision PDO', 'Propopdo.datedecisionpdo' );?></th>
                    <th><?php echo $paginator->sort( 'Motif', 'Propopdo.motifpdo' );?></th>

                    <th class="action">Action</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $commissionapre as $index => $apre ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $apre['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $apre['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $apre['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $apre['Dossier']['numdemrsa'];


                    echo $html->tableCells(
                        array(
                            h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                            h( Set::extract( $apre, 'Dossier.matricule' ) ),
                            h( Set::extract( $apre, 'Adresse.locaadr' ) ),
                            h( date_short( Set::extract( $apre, 'Dossier.dtdemrsa' ) ) ),
                            h( value( $typepdo, Set::extract( 'Propopdo.typepdo_id', $apre ) ) ),
                            h( value( $decisionpdo, Set::extract( 'Propopdo.decisionpdo_id', $apre ) ) ),
                            h( date_short( Set::extract( 'Apre.datedemandeapre', $apre ) ) ),
                            h( value( $motifpdo, Set::extract( 'Propopdo.motifpdo', $apre ) ) ),
                            $html->viewLink(
                                'Voir le contrat « '.$title.' »',
                                array( 'controller' => 'dossierspdo', 'action' => 'index', $apre['Dossier']['id'] )
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
        <?php echo $pagination;?>
       <ul class="actionMenu">
            <li><?php
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;' )
                );
            ?></li>

            <!--<li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'commissionsapre', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>-->
        </ul>
        <?php echo $form->end();?>


    <?php else:?>
        <p>Aucune APRE dans la commission.</p>
    <?php endif?>
<?php endif?>