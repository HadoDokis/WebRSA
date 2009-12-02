<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Visualisation des décisions des recours';?>

<h1>Décision des recours</h1>

<?php
    if( isset( $recoursapres ) ) {
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

<?php if( isset( $recoursapres ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $recoursapres ) && count( $recoursapres ) > 0 ):?>
        <?php echo $form->create( 'RecoursApre', array( 'url'=> Router::url( null, true ) ) );?>
    <?php echo $pagination;?> 
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° demande APRE', 'Apre.numeroapre' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' );?></th>
                    <th><?php echo $paginator->sort( 'Décision comité examen', 'ApreComiteapre.decisioncomite' );?></th>
                    <th><?php echo $paginator->sort( 'Date de décision comité', 'Comiteapre.datecomite' );?></th>
                    <th>Demande de recours</th>
                    <th>Date demande de recours</th>
                    <th>Observations</th>

                    <th class="action">Action</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $recoursapres as $index => $recours ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $recours['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $recours['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $recours['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $recours['Dossier']['numdemrsa'];

// debug($recours);
                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $recours, 'Apre.numeroapre' ) ),
                            h( Set::classicExtract( $recours, 'Personne.qual' ).' '.Set::classicExtract( $recours, 'Personne.nom' ).' '.Set::classicExtract( $recours, 'Personne.prenom' ) ),
                            h( Set::classicExtract( $recours, 'Adresse.locaadr' ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'Apre.datedemandeapre' ) ) ),
                            h( Set::enum( Set::classicExtract( $recours, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'Comiteapre.datecomite' ) ) ),
                            h( Set::enum( Set::classicExtract( $recours, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'ApreComiteapre.daterecours' ) ) ),
                            h( Set::classicExtract( $recours, 'ApreComiteapre.observationrecours' ) ),
                            $html->viewLink(
                                'Voir',
                                array( 'controller' => 'comitesapres', 'action' => 'index', Set::classicExtract( $recours, 'Comiteapre.id' ) )
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
                    array( 'controller' => 'recoursapres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
        <?php echo $form->end();?>


    <?php else:?>
        <p>Aucune APRE en recours présente dans la cohorte.</p>
    <?php endif?>
<?php endif?>