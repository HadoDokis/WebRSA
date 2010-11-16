<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( !empty( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$xhtml->link(
            $xhtml->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Cohortecomiteapre' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $xform->create( 'Cohortecomiteapre', array( 'url'=> Router::url( null, true ), 'id' => 'Cohortecomiteapre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CohortecomiteapreDatecomite', $( 'CohortecomiteapreDatecomiteFromDay' ).up( 'fieldset' ), false );
    });
</script>

 <fieldset class= "noprint">
        <legend>Recherche Comités</legend>
        <?php echo $xform->input( 'Cohortecomiteapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
            <?php echo $xform->input( 'Cohortecomiteapre.id', array( 'label' => 'Intitulé du comité', 'options' => $comitesapre ) );?>
            <?php echo $xform->input( 'Cohortecomiteapre.datecomite', array( 'label' => 'Filtrer par date de comités', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du comité</legend>
                <?php
                    $datecomite_from = Set::check( $this->data, 'Cohortecomiteapre.datecomite_from' ) ? Set::extract( $this->data, 'Cohortecomiteapre.datecomite_from' ) : strtotime( '-1 week' );
                    $datecomite_to = Set::check( $this->data, 'Cohortecomiteapre.datecomite_to' ) ? Set::extract( $this->data, 'Cohortecomiteapre.datecomite_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Cohortecomiteapre.datecomite_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_from ) );?>
                <?php echo $xform->input( 'Cohortecomiteapre.datecomite_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_to ) );?>
            </fieldset>
    </fieldset>
    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $xform->end();?>
