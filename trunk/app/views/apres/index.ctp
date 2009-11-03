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
                <th>Nom/Prénom Allocataire</th>
                <th>Structure référente</th>
                <th>Permanence liée</th>
                <th>Type de RDV</th>
                <th>Statut du RDV</th>
                <th>Date du RDV</th>
                <th>Heure du RDV</th>
                <th>Objet du RDV</th>
                <th>Commentaire suite au RDV</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $apres as $apre ) {

                    echo $html->tableCells(
                        array(
                            h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                            h( Set::extract( $struct, Set::extract( $apre, 'Apre.structurereferente_id' ) ) ),
                            h( Set::extract( $permanences, Set::extract( $apre, 'Apre.permanence_id' ) ) ),
                            h( Set::extract( $apre, 'Typerdv.libelle' ) ),
                            h( value( $statutrdv, Set::classicExtract( $apre, 'Apre.statutrdv_id' ) ) ),
                            h( date_short( Set::extract( $apre, 'Apre.daterdv' ) ) ),
                            h( $locale->date( 'Time::short', Set::classicExtract( $apre, 'Apre.heurerdv' ) ) ),
                            h( Set::extract( $apre, 'Apre.objetrdv' ) ),
                            h( Set::extract( $apre, 'Apre.commentairerdv' ) ) ,
                            $html->viewLink(
                                'Voir le rendez-vous',
                                array( 'controller' => 'apres', 'action' => 'view', $apre['Apre']['id'] ),
                                $permissions->check( 'apres', 'view' )
                            ),
                            $html->editLink(
                                'Editer le rendez-vous',
                                array( 'controller' => 'apres', 'action' => 'edit', $apre['Apre']['id'] ),
                                $permissions->check( 'apres', 'edit' )
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