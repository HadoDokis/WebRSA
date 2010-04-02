<?php  $this->pageTitle = 'Référents liés à la personne';?>
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
    <h1>Référents</h1>

        <?php if( empty( $personnes_referents ) ):?>
            <p class="notice">Cette personne ne possède pas encore de référents.</p>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter un Référent',
                        array( 'controller' => 'personnes_referents', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

        <?php if( !empty( $personnes_referents ) ):?>
        <?php
            $cloture = Set::classicExtract( $pers, 'PersonneReferent.dernier.dfdesignation' );
            $cloture = ( !empty( $cloture ) );
        ?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter un Référent',
                            array( 'controller' => 'personnes_referents', 'action' => 'add', $personne_id ), $cloture
                        ).' </li>';
                    ?>
                </ul>
        <?php endif;?>


    <?php if( !empty( $personnes_referents ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>Nom/Prénom Référent</th>
                <th>Fonction</th>
                <th>N° Téléphone</th>
                <th>Email</th>
                <th>Structure référente</th>
                <th>Date de désignation</th>
                <th>Fin de désignation</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $personnes_referents as $personne_referent ) {

                    $cloture = Set::classicExtract( $personne_referent, 'PersonneReferent.dfdesignation' );
                    $cloture = ( !empty( $cloture ) );

                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $personne_referent, 'Referent.qual' ).' '.Set::classicExtract( $personne_referent, 'Referent.nom' ).' '.Set::classicExtract( $personne_referent, 'Referent.prenom' ) ),
                            h( Set::classicExtract( $personne_referent, 'Referent.fonction' ) ),
                            h( Set::classicExtract( $personne_referent, 'Referent.numero_poste' ) ),
                            h( Set::classicExtract( $personne_referent, 'Referent.email' ) ),
                            h( value( $struct, Set::extract( $personne_referent, 'Referent.structurereferente_id' ) ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dddesignation' ) ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dfdesignation' ) ) ),

                            $html->editLink(
                                'Editer le référent',
                                array( 'controller' => 'personnes_referents', 'action' => 'edit',
                                $personne_referent['PersonneReferent']['id'] ),
                                !$cloture
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