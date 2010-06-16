<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( !empty( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Cohortepdo' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $form->create( 'Cohortepdo', array( 'url'=> Router::url( null, true ), 'id' => 'Cohortepdo', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CohortepdoDatedecisionpdo', $( 'CohortepdoDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
    });
</script>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Cohortepdo.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Cohortepdo.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
    </fieldset>
 <fieldset class= "noprint">
        <legend>Recherche PDO</legend>
        <?php /*echo $form->input( 'Cohortepdo.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );*/?>
        <?php if( $this->action == 'avisdemande' ):?>
            <?php echo $form->input( 'Cohortepdo.matricule', array( 'label' => 'N° CAF', 'type' => 'text', 'maxlength' => 15 ) );?>
            <?php echo $form->input( 'Cohortepdo.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
			<?php
				if( Configure::read( 'CG.cantons' ) ) {
					echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
			?>
        <?php else :?>
        <?php echo $form->input( 'Cohortepdo.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
        <?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
		<?php
            echo $form->input( 'Cohortepdo.typepdo_id', array( 'label' =>  ( __( 'typepdo', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
            echo $form->input( 'Cohortepdo.decisionpdo_id', array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
            echo $form->input( 'Cohortepdo.motifpdo', array( 'label' => __( 'motifpdo', true ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );
            echo $form->input( 'Cohortepdo.user_id', array( 'label' => __d( 'propopdo', 'Propopdo.user_id', true ), 'type' => 'select', 'options' => $gestionnaire, 'empty' => true ) );
        ?>
            <?php echo $form->input( 'Cohortepdo.datedecisionpdo', array( 'label' => 'Filtrer par date de décision des PDOs', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du contrat</legend>
                <?php
                    $datedecisionpdo_from = Set::check( $this->data, 'Cohortepdo.datedecisionpdo_from' ) ? Set::extract( $this->data, 'Cohortepdo.datedecisionpdo_from' ) : strtotime( '-1 week' );
                    $datedecisionpdo_to = Set::check( $this->data, 'Cohortepdo.datedecisionpdo_to' ) ? Set::extract( $this->data, 'Cohortepdo.datedecisionpdo_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Cohortepdo.datedecisionpdo_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_from ) );?>
                <?php echo $form->input( 'Cohortepdo.datedecisionpdo_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_to ) );?>
            </fieldset>
        <?php endif;?>
    </fieldset>
    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>