<?php  $this->pageTitle = 'APRE liée la personne';?>
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

    <?php if( empty( $apres ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'aide personnalisée de retour à l'emploi (APRE).</p>
    <?php endif;?>


    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter APRE',
                array( 'controller' => 'apres', 'action' => 'add', $personne_id )
            ).' </li>';
        ?>
    </ul>


    <?php if( !empty( $apres ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>N° APRE</th>
                <th>Nom/Prénom Allocataire</th>
                <th>Type de demande APRE</th>
                <th>Référent APRE</th>
                <th>Date demande APRE</th>
                <th>Nature de la demande</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $apres as $apre ) {
                    $aidesApre = array();
                    $naturesaide = Set::classicExtract( $apre, 'Natureaide' );
                    foreach( $naturesaide as $natureaide => $nombre ) {
                        if( $nombre > 0 ) {
                            $aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
                        }
                    }
//debug($apres);
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
                                array( 'controller' => 'gedooos', 'action' => 'apre', 'class' => 'external', $apre['Apre']['id'] ),
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


</div>
<div class="clearer"><hr /></div>