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
        observeDisableFieldsetOnCheckbox( 'CriterecomiteexamenapreDatecomite', $( 'CriterecomiteexamenapreDatecomiteFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'CriterecomiteexamenapreHeurecomite', $( 'CriterecomiteexamenapreHeurecomiteFromHour' ).up( 'fieldset' ), false );
    });
</script>

<?php echo $xform->create( 'Criterecomiteexamenapre', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

    <fieldset>
            <?php echo $xform->input( 'Criterecomiteexamenapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <?php echo $xform->input( 'Criterecomiteexamenapre.datecomite', array( 'label' => 'Filtrer par date de Comité d\'examen', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Comité</legend>
                <?php
                    $datecomite_from = Set::check( $this->data, 'Criterecomiteexamenapre.datecomite_from' ) ? Set::extract( $this->data, 'Criterecomiteexamenapre.datecomite_from' ) : strtotime( '-1 week' );
                    $datecomite_to = Set::check( $this->data, 'Criterecomiteexamenapre.datecomite_to' ) ? Set::extract( $this->data, 'Criterecomiteexamenapre.datecomite_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Criterecomiteexamenapre.datecomite_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_from ) );?>
                <?php echo $xform->input( 'Criterecomiteexamenapre.datecomite_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_to ) );?>
            </fieldset>
            <!-- <?php echo $xform->input( 'Criterecomiteexamenapre.heurecomite', array( 'label' => 'Filtrer par heure de Comité d\'examen', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Comité</legend>
                <?php
                    $heurecomite_from = Set::check( $this->data, 'Criterecomiteexamenapre.heurecomite_from' ) ? Set::extract( $this->data, 'Criterecomiteexamenapre.heurecomite_from' ) : strtotime( '-1 hour' );
                    $heurecomite_to = Set::check( $this->data, 'Criterecomiteexamenapre.heurecomite_to' ) ? Set::extract( $this->data, 'Criterecomiteexamenapre.heurecomite_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Criterecomiteexamenapre.heurecomite_from', array( 'label' =>  'De', 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5, 'hourRange' => array( 8, 19 ), 'selected' => $heurecomite_from ) );?>
                <?php echo $xform->input( 'Criterecomiteexamenapre.heurecomite_to', array( 'label' =>  'A ', 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5, 'hourRange' => array( 8, 19 ), 'selected' => $heurecomite_to ) );?>
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
                                h( date_short( Set::classicExtract( $comiteapre, 'Comiteexamenapre.datecomite' ) ) ),
                                h( $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteexamenapre.heurecomite' ) ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteexamenapre.lieucomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteexamenapre.intitulecomite' ) ),
                                h( Set::classicExtract( $comiteapre, 'Comiteexamenapre.observationcomite' ) ),
                                $html->viewLink(
                                    'Voir la relance',
                                    array( 'controller' => 'comitesexamenapres', 'action' => 'index', Set::classicExtract( $comiteapre, 'Comiteexamenapre.id' ) ),
                                    $permissions->check( 'comitesexamenapres', 'index' )
                                ),
                                $html->editLink(
                                    'Editer la relance',
                                    array( 'controller' => 'comitesexamenapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteexamenapre.id' ) ),
                                    $permissions->check( 'comitesexamenapres', 'edit' )
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
    <?php if( $permissions->check( 'comitesexamenapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter Comité',
                array( 'controller' => 'comitesexamenapres', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <?php endif;?>
<?php endif?>