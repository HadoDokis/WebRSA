<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

<?php
//     if( !empty( $this->data ) ) {
//         echo '<ul class="actionMenu"><li>'.$html->link(
//             $html->image(
//                 'icons/application_form_magnify.png',
//                 array( 'alt' => '' )
//             ).' Formulaire',
//             '#',
//             array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Cohortepdo' ).toggle(); return false;" )
//         ).'</li></ul>';
//     }

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

    if( isset( $cohortepdo ) ) {
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

<?php if( isset( $cohortepdo ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
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
                <?php foreach( $cohortepdo as $index => $pdo ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $pdo['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $pdo['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $pdo['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $pdo['Dossier']['numdemrsa'];


                    echo $html->tableCells(
                        array(
                            h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
                            h( Set::extract( $pdo, 'Dossier.matricule' ) ),
                            h( Set::extract( $pdo, 'Adresse.locaadr' ) ),
                            h( date_short( Set::extract( $pdo, 'Dossier.dtdemrsa' ) ) ),
                            h( value( $typepdo, Set::extract( 'Propopdo.typepdo_id', $pdo ) ) ),
                            h( value( $decisionpdo, Set::extract( 'Propopdo.decisionpdo_id', $pdo ) ) ),
                            h( date_short( Set::extract( 'Propopdo.datedecisionpdo', $pdo ) ) ),
                            h( value( $motifpdo, Set::extract( 'Propopdo.motifpdo', $pdo ) ) ),
                            $html->viewLink(
                                'Voir le contrat « '.$title.' »',
                                array( 'controller' => 'dossierspdo', 'action' => 'index', $pdo['Dossier']['id'] )
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

            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'cohortespdos', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
        <?php echo $form->end();?>


    <?php else:?>
        <p>Aucune PDO dans la cohorte.</p>
    <?php endif?>
<?php endif?>