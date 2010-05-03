<?php
    $this->pageTitle = sprintf( 'APREs liées à %s', $personne['Personne']['nom_complet'] );
    $this->modelClass = $this->params['models'][0];
?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

            <?php if( empty( $apres ) ):?>
                <p class="notice">Cette personne ne possède pas encore d'APRE.</p>
            <?php endif;?>
            <?php if( $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'add' ) ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter APRE',
                            array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'add', $personne_id )
                        ).' </li>';
                    ?>
                </ul>
            <?php endif;?>

    <?php if( !empty( $apres ) ):?>
    <?php
        if( $alerteMontantAides ) {
            echo $html->tag(
                'p',
                $html->image( 'icons/error.png', array( 'alt' => 'Remarque' ) ).' '.sprintf( 'Cette personne risque de bénéficier de plus de %s € d\'aides complémentaires au cours des %s derniers mois', Configure::read( 'Apre.montantMaxComplementaires' ), Configure::read( 'Apre.periodeMontantMaxComplementaires' ) ),
                array( 'class' => 'error' )
            );
        }
    ?>

   <table class="tooltips">
        <thead>
            <tr>
                <th>Date demande APRE</th>
                <th>Etat du dossier</th>
                <th>Thème de l'aide</th>
                <th>Type d'aides</th>
                <th>Montant demandé</th>
                <th>Montant accordé</th>
                <th>Décision</th>
                <th colspan="4" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $apres as $index => $apre ) {

                    $statutApre = Set::classicExtract( $apre, "{$this->modelClass}.statutapre" );


                    $etat = Set::enum( Set::classicExtract( $apre, "{$this->modelClass}.etatdossierapre" ),$options['etatdossierapre'] );
                    $mtforfait = Set::classicExtract( $apre, 'Aideapre66.montantaide' );
                    $mtattribue = Set::classicExtract( $apre, 'Aideapre66.montantaccorde' );

                    $buttonEnabled = true;
                    if( $etat == 'Complet' ){
                        $buttonEnabledInc = false;
                    }
                    else{
                        $buttonEnabledInc = true;
                    }


                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>N° APRE</th>
                                <td>'.h( Set::classicExtract( $apre, "{$this->modelClass}.numeroapre" ) ).'</td>
                            </tr>
                            <tr>
                                <th>Nom/Prénom Allocataire</th>
                                <td>'.h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ).'</td>
                            </tr>
                            <tr>
                                <th>Référent APRE</th>
                                <td>'.h( Set::enum( Set::classicExtract( $apre, "{$this->modelClass}.referent_id" ), $referents ) ).'</td>
                            </tr>
                            <tr>
                                <th>Natures de la demande</th>
                                <td>'.( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ).'</td>
                            </tr>
                        </tbody>
                    </table>';

                    echo $html->tableCells(
                        array(
                            h( date_short( Set::classicExtract( $apre, 'Aideapre66.datedemande' ) ) ),
                            h( $etat ),
                            h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.themeapre66_id' ), $themes  ) ),
                            h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.typeaideapre66_id' ), $nomsTypeaide  ) ),
                            h( $locale->money( $mtforfait ) ),
                            h( $locale->money( $mtattribue ) ),
                            h(  Set::enum( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ), $options['decisionapre'] ) ),
                            $html->viewLink(
                                'Voir la demande APRE',
                                array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'view', $apre[$this->modelClass]['id'] ),
                                $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'view' )
                            ),
                            $html->editLink(
                                'Editer la demande APRE',
                                array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'edit', $apre[$this->modelClass]['id'] ),
                                $buttonEnabled,
                                $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'edit' )
                            ),
                            $html->relanceLink(
                                'Relancer la demande APRE',
                                array( 'controller' => 'relancesapres', 'action' => 'add', $apre[$this->modelClass]['id'] ),
                                $buttonEnabledInc,
                                $permissions->check( 'relancesapres', 'add' ) && ( $apre[$this->modelClass]['etatdossierapre'] == 'INC' )
                            ),
                            $html->printLink(
                                'Imprimer la demande APRE',
                                array( 'controller' => 'gedooos', 'action' => 'apre', $apre[$this->modelClass]['id'] ),
                                $buttonEnabled,
                                $permissions->check( 'gedooos', 'apre' )
                            ),
                            array( $innerTable, array( 'class' => 'innerTableCell' ) )
                        ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                }
            ?>
        </tbody>
    </table>
<?php endif;?>

<br />

    <?php if( !empty( $apres ) ):?>

        <h2>Liste des relances</h2>
        <?php if( empty( $relancesapres ) ):?>
            <p class="notice">Cette personne ne possède pas encore de relances.</p>
        <?php endif;?>

        <?php if( !empty( $apres ) && !empty( $relancesapres ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>N° Apre</th>
                    <th>Date de relance</th>
                    <th>Liste des pièces manquantes</th>
                    <th>Commentaire</th>
                    <th colspan="3" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $relancesapres as $relanceapre ) {
                        $piecesAbsentes = Set::extract( $relanceapre, '/Relanceapre/Piecemanquante/libelle' );
                        $piecesManquantesAides = Set::classicExtract( $relanceapre, "{$this->modelClass}.Piece.Manquante" );

                        $textePiecesManquantes = '';
                        foreach( $piecesManquantesAides as $model => $pieces ) {
                            if( !empty( $pieces ) ) {
                                $textePiecesManquantes .= $html->tag( 'h3', __d( 'apre', $model, true ) ).'<ul><li>'.implode( '</li><li>', $pieces ).'</li></ul>';
                            }
                        }

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $relanceapre, "{$this->modelClass}.numeroapre" ) ),
                                h( date_short( Set::classicExtract( $relanceapre, 'Relanceapre.daterelance' ) ) ),
                                $textePiecesManquantes,
                                h( Set::classicExtract( $relanceapre, 'Relanceapre.commentairerelance' ) ),
//                                 $html->viewLink(
//                                     'Voir la relance',
//                                     array( 'controller' => 'relancesapres', 'action' => 'view', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
//                                     $permissions->check( 'relancesapres', 'view' )
//                                 ),
                                $html->editLink(
                                    'Editer la relance',
                                    array( 'controller' => 'relancesapres', 'action' => 'edit', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'relancesapres', 'edit' )
                                ),
                                $html->printLink(
                                    'Imprimer la notification de relance',
                                    array( 'controller' => 'gedooos', 'action' => 'relanceapre', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'gedooos', 'relanceapre' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>
        <?php  endif;?>
    <?php  endif;?>
</div>
<div class="clearer"><hr /></div>