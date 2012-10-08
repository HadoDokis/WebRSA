<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	if( !empty( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
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

	<?php echo $xform->input( 'Recoursapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
	<?php
		echo $search->blocAllocataire();
		echo $search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $search->etatdosrsa($etatdosrsa);
		?>
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
			<?php echo $xform->input( 'Recoursapre.datedemandeapre_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_from ) );?>
			<?php echo $xform->input( 'Recoursapre.datedemandeapre_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_to ) );?>
		</fieldset>
	</fieldset>
	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $xform->end();?>