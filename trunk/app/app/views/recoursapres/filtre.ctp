<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( !empty( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Recoursapre' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $xform->create( 'Recoursapre', array( 'url'=> Router::url( null, true ), 'id' => 'Recoursapre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'RecoursapreDatedemandeapre', $( 'RecoursapreDatedemandeapreFromDay' ).up( 'fieldset' ), false );
    });
</script>

 <fieldset class= "noprint">
        <legend>Recherche Recours</legend>
        <?php echo $xform->input( 'Recoursapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <fieldset>
            <legend>Recherche par personne</legend>
            <?php echo $xform->input( 'Recoursapre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Recoursapre.numdemrsa', array( 'label' => 'N° dossier RSA ', 'type' => 'text', 'maxlength' => 11 ) );?>
            <?php echo $form->input( 'Recoursapre.matricule', array( 'label' => 'N° CAF ', 'type' => 'text'/*, 'maxlength' => 11*/ ) );?>
            <?php echo $xform->input( 'Recoursapre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
            <?php echo $xform->input( 'Recoursapre.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
        </fieldset>
        <fieldset>
            <legend>Recherche par demande APRE</legend>
            <?php echo $form->input( 'Recoursapre.numeroapre', array( 'label' => 'N° demande APRE ', 'type' => 'text', 'maxlength' => 16 ) );?>
            <?php echo $xform->input( 'Recoursapre.datedemandeapre', array( 'label' => 'Filtrer par date de demande APRE', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date du demande APRE</legend>
                <?php
                    $datedemandeapre_from = Set::check( $this->data, 'Recoursapre.datedemandeapre_from' ) ? Set::extract( $this->data, 'Recoursapre.datedemandeapre_from' ) : strtotime( '-1 week' );
                    $datedemandeapre_to = Set::check( $this->data, 'Recoursapre.datedemandeapre_to' ) ? Set::extract( $this->data, 'Recoursapre.datedemandeapre_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Recoursapre.datedemandeapre_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_from ) );?>
                <?php echo $xform->input( 'Recoursapre.datedemandeapre_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_to ) );?>
            </fieldset>
        </fieldset>
    </fieldset>
    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $xform->end();?>
