<?php  $this->pageTitle = 'Rendez-vous de la personne';?>
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
    <h1>Rendez-vous</h1>

    <?php if( empty( $rdvs ) ):?>
        <p class="notice">Cette personne ne possède pas encore de rendez-vous.</p>
    <?php endif;?>

    <?php if( $permissions->check( 'rendezvous', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter un RDV',
                    array( 'controller' => 'rendezvous', 'action' => 'add', $personne_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $rdvs ) ):?>
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
                foreach( $rdvs as $rdv ) {

                    echo $html->tableCells(
                        array(
                            h( $rdv['Personne']['nom'].' '.$rdv['Personne']['prenom'] ),
                            h( Set::extract( $struct, Set::extract( $rdv, 'Rendezvous.structurereferente_id' ) ) ),
                            h( Set::extract( $permanences, Set::extract( $rdv, 'Rendezvous.permanence_id' ) ) ),
                            h( Set::extract( $rdv, 'Typerdv.libelle' ) ),
                            h( value( $statutrdv, Set::classicExtract( $rdv, 'Rendezvous.statutrdv' ) ) ),
                            h( date_short( Set::extract( $rdv, 'Rendezvous.daterdv' ) ) ),
                            h( $locale->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) ) ),
                            h( Set::extract( $rdv, 'Rendezvous.objetrdv' ) ),
                            h( Set::extract( $rdv, 'Rendezvous.commentairerdv' ) ) ,
                            $html->viewLink(
                                'Voir le rendez-vous',
                                array( 'controller' => 'rendezvous', 'action' => 'view', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'rendezvous', 'view' )
                            ),
                            $html->editLink(
                                'Editer le rendez-vous',
                                array( 'controller' => 'rendezvous', 'action' => 'edit', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'rendezvous', 'edit' )
                            ),
                            $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'rendezvous', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'gedooos', 'rendezvous' )
                            ),
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