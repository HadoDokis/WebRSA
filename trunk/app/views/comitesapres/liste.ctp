<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Liste des Comités d\'examen APRE';?>

<h1>Liste des Comités d'examen</h1>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }

?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'ComiteapreDatecomite', $( 'ComiteapreDatecomiteFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'ComiteapreHeurecomite', $( 'ComiteapreHeurecomiteFromHour' ).up( 'fieldset' ), false );
    });
</script>

<?php echo $xform->create( 'Comiteapre', array( 'type' => 'post', 'action' => '/liste/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

    <fieldset>
            <?php echo $xform->input( 'Comiteapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <?php echo $xform->input( 'Comiteapre.datecomite', array( 'label' => 'Filtrer par date de Comité d\'examen', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Comité</legend>
                <?php
                    $datecomite_from = Set::check( $this->data, 'Comiteapre.datecomite_from' ) ? Set::extract( $this->data, 'Comiteapre.datecomite_from' ) : strtotime( '-1 week' );
                    $datecomite_to = Set::check( $this->data, 'Comiteapre.datecomite_to' ) ? Set::extract( $this->data, 'Comiteapre.datecomite_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Comiteapre.datecomite_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_from ) );?>
                <?php echo $xform->input( 'Comiteapre.datecomite_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_to ) );?>
            </fieldset>

    </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>

<!-- Résultats -->
<?php if( isset( $comitesapres ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $comitesapres ) && count( $comitesapres ) > 0  ):?>

        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Intitulé du comité</th>
                    <th>Lieu du comité</th>
                    <th>Date du comité</th>
                    <th>Heure du comité</th>
                    <th>Nb de participants</th>
                    <th>Nb d'absents</th>
                    <th>Nb de demandes à traiter</th>
                    <th>Description</th>
                    <th colspan="3" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comitesapres as $comiteapre ) {
// debug($comiteapre);
                        $comiteapre = $comiteapre['Comiteapre'];
                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $comiteapre, 'intitulecomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'lieucomite' ) ),
                                h( date_short( Set::classicExtract( $comiteapre, 'datecomite' ) ) ),
                                h( $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'heurecomite' ) ) ),
                                h( Set::classicExtract( $comiteapre, 'Participantpresent.Comiteapre' ) ),
                                h( Set::classicExtract( $comiteapre, 'Participantabsent.Comiteapre' ) ),
                                h( Set::classicExtract( $comiteapre, 'intitulecomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'ApreComiteapre.observationcomite' ) ),
                                $html->viewLink(
                                    'Voir le comité',
                                    array( 'controller' => 'comitesapres', 'action' => 'view', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
                                    $permissions->check( 'comitesapres', 'index' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>
    <ul class="actionMenu">
        <li><?php
            echo $html->exportLink(
                'Télécharger le tableau',
                array( 'controller' => 'comitesapres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
            );
        ?></li>
    </ul>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun comité.</p>
    <?php endif?>
<?php endif?>