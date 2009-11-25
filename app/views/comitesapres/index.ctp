<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Comité examen APRE';?>

<h1>Recherche de Comité</h1>

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

<?php echo $xform->create( 'Comiteapre', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

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
            <!-- <?php echo $xform->input( 'Comiteapre.heurecomite', array( 'label' => 'Filtrer par heure de Comité d\'examen', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Comité</legend>
                <?php
                    $heurecomite_from = Set::check( $this->data, 'Comiteapre.heurecomite_from' ) ? Set::extract( $this->data, 'Comiteapre.heurecomite_from' ) : strtotime( '-1 hour' );
                    $heurecomite_to = Set::check( $this->data, 'Comiteapre.heurecomite_to' ) ? Set::extract( $this->data, 'Comiteapre.heurecomite_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Comiteapre.heurecomite_from', array( 'label' =>  'De', 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5, 'hourRange' => array( 8, 19 ), 'selected' => $heurecomite_from ) );?>
                <?php echo $xform->input( 'Comiteapre.heurecomite_to', array( 'label' =>  'A ', 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5, 'hourRange' => array( 8, 19 ), 'selected' => $heurecomite_to ) );?>
            </fieldset> -->

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
                    <th>Date du comité</th>
                    <th>Heure du comité</th>
                    <th>Lieu du comité</th>
                    <th>Intitulé du comité</th>
                    <th>Observations du comité</th>
                    <th colspan="3" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comitesapres as $comiteapre ) {

                        echo $html->tableCells(
                            array(
                                h( date_short( Set::classicExtract( $comiteapre, 'Comiteapre.datecomite' ) ) ),
                                h( $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteapre.heurecomite' ) ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteapre.lieucomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteapre.intitulecomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteapre.observationcomite' ) ),
                                $html->viewLink(
                                    'Voir le comité',
                                    array( 'controller' => 'comitesapres', 'action' => 'view', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
                                    $permissions->check( 'comitesapres', 'index' )
                                ),
                                $html->editLink(
                                    'Editer la relance',
                                    array( 'controller' => 'comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
                                    $permissions->check( 'comitesapres', 'edit' )
                                ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun comité.</p>
    <?php endif?>
    <?php if( $permissions->check( 'comitesapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter Comité',
                array( 'controller' => 'comitesapres', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <?php endif;?>
<?php endif?>