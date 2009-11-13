<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( !empty( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Commissionapre' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $form->create( 'Commissionapre', array( 'url'=> Router::url( null, true ), 'id' => 'Commissionapre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CommissionapreDatedemandeapre', $( 'CommissionapreDatedemandeapreFromDay' ).up( 'fieldset' ), false );
    });
</script>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Commissionapre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Commissionapre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
    </fieldset>
 <fieldset class= "noprint">
        <legend>Recherche PDO</legend>
        <?php echo $form->input( 'Commissionapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php if( $this->action == 'avisdemande' ):?>
            <?php echo $form->input( 'Commissionapre.matricule', array( 'label' => 'N° CAF', 'type' => 'text', 'maxlength' => 15 ) );?>
            <?php echo $form->input( 'Commissionapre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
            <?php
                if( Configure::read( 'CG.cantons' ) ) {
                    echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
                }
            ?>
        <?php else :?>
        <?php echo $form->input( 'Commissionapre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
        <?php
            if( Configure::read( 'CG.cantons' ) ) {
                echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
            }
        ?>

            <?php echo $form->input( 'Commissionapre.datedemandeapre', array( 'label' => 'Filtrer par date de demande d\'APREs', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de demande d'APRE</legend>
                <?php
                    $datedemandeapre_from = Set::check( $this->data, 'Commissionapre.datedemandeapre_from' ) ? Set::extract( $this->data, 'Commissionapre.datedemandeapre_from' ) : strtotime( '-1 week' );
                    $datedemandeapre_to = Set::check( $this->data, 'Commissionapre.datedemandeapre_to' ) ? Set::extract( $this->data, 'Commissionapre.datedemandeapre_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Commissionapre.datedemandeapre_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_from ) );?>
                <?php echo $form->input( 'Commissionapre.datedemandeapre_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_to ) );?>
            </fieldset>
        <?php endif;?>
    </fieldset>
    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>