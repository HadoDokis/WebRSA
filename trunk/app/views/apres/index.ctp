<?php  $this->pageTitle = 'APRE liée à la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

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
?>

<div class="with_treemenu">
    <h1>APRE</h1>
    <?php if( empty( $contratinsertion )  ):?>
        <p class="error">Impossible d'ajouter une demande d'APRE lorsqu'il n'existe pas de contrat d'insertion.</p>

    <?php elseif( !empty( $contratinsertion ) && empty( $refsapre ) ):?>
        <p class="error">Impossible d'ajouter une demande d'APRE lorsqu'il n'existe pas de référent pour l'APRE.</p>

    <?php else:?>
        <?php if( empty( $apres ) ):?>
            <p class="notice">Cette personne ne possède pas encore d'aide personnalisée de retour à l'emploi (APRE).</p>
        <?php endif;?>

        <?php if( $permissions->check( 'apres', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter APRE',
                        array( 'controller' => 'apres', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php if( !empty( $apres ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>N° APRE</th>
                <th>Nom/Prénom Allocataire</th>
                <th>Type de demande APRE</th>
                <th>Référent APRE</th>
                <th>Date demande APRE</th>
                <th>Natures de la demande</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $apres as $apre ) {
                    $aidesApre = array();
                    $naturesaide = Set::classicExtract( $apre, 'Natureaide' );
//                     debug($naturesaide);
                    foreach( $naturesaide as $natureaide => $nombre ) {
                        if( $nombre > 0 ) {
                            $aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
                        }
                    }

                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                            h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                            h( Set::classicExtract( $options['typedemandeapre'], Set::classicExtract( $apre, 'Apre.typedemandeapre' ) ) ),
                            h( Set::classicExtract( $refsapre, Set::classicExtract( $apre, 'Apre.referentapre_id' ) ) ),
                            h( date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),
                            ( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ),

                            $html->viewLink(
                                'Voir la demande APRE',
                                array( 'controller' => 'apres', 'action' => 'view', $apre['Apre']['id'] ),
                                $permissions->check( 'apres', 'view' )
                            ),
                            $html->editLink(
                                'Editer la demande APRE',
                                array( 'controller' => 'apres', 'action' => 'edit', $apre['Apre']['id'] ),
                                $permissions->check( 'apres', 'edit' )
                            ),
                            $html->printLink(
                                'Imprimer la demande APRE',
                                array( 'controller' => 'gedooos', 'action' => 'apre', $apre['Apre']['id'] ),
                                $permissions->check( 'gedooos', 'apre' )
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

<br />

    <?php if( !empty( $apres ) ):?>

        <h2>Liste des relances</h2>
        <?php if( empty( $relancesapres ) ):?>
            <p class="notice">Cette personne ne possède pas encore de relances.</p>
        <?php endif;?>
            <?php if( $permissions->check( 'relancesapres', 'add' ) ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter Relance',
                            array( 'controller' => 'relancesapres', 'action' => 'add', $personne_id )
                        ).' </li>';
                    ?>
                </ul>
            <?php endif;?>
        <?php if( !empty( $apres ) && !empty( $relancesapres ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>N° Apre</th>
                    <th>Date de relance</th>
                    <th>Etat du dossier</th>
                    <th>Liste des pièces fournies mais il faut manquantes (FIXME)</th>
                    <th>Commentaire</th>
                    <th colspan="3" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $relancesapres as $relanceapre ) {
                        $piecesAbsentes = array();
                        $piecesPresentesLibelle = Set::classicExtract( $apre, 'Pieceapre.{n}.id' );

                        foreach(  $piecesPresentesLibelle as $pieceapre ) {
                            if(  !empty( $pieceapre ) )  {
                                $piecesAbsentes[] = Set::classicExtract( $piecesapre, $pieceapre );
                            }
                        }

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                                h( date_short( Set::classicExtract( $relanceapre, 'Relanceapre.daterelance' ) ) ),
                                h( Set::enum( Set::classicExtract( $relanceapre, 'Relanceapre.etatdossierapre' ), $options['etatdossierapre'] ) ),
                                ( empty( $piecesAbsentes ) ? null :'<ul><li>'.implode( '</li><li>', $piecesAbsentes ).'</li></ul>' ),
                                h( Set::classicExtract( $relanceapre, 'Relanceapre.commentairerelance' ) ),
                                $html->viewLink(
                                    'Voir la relance',
                                    array( 'controller' => 'relancesapres', 'action' => 'view', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'relancesapres', 'view' )
                                ),
                                $html->editLink(
                                    'Editer la relance',
                                    array( 'controller' => 'relancesapres', 'action' => 'edit', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'relancesapres', 'edit' )
                                ),
                                $html->printLink(
                                    'Imprimer la notification de relance',
                                    array( 'controller' => 'gedooos', 'action' => 'apre', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'gedooos', 'apre' )
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