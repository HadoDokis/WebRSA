<?php
	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'SearchActioncandidatPersonneDatesignature', $( 'SearchActioncandidatPersonneDatesignatureFromDay' ).up( 'fieldset' ), false );
	});
</script>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'SearchActioncandidatId', 'SearchPartenaireId' );
	});
</script>

<?php echo $xform->create( 'Cohortefichecandidature66', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Fiche de candidature</legend>
			<?php
				echo $default2->subform(
					array(
						'Search.Partenaire.codepartenaire' => array( 'type' => 'text', 'label' => __d( 'partenaire', 'Partenaire.codepartenaire', true ) ),
						'Search.Partenaire.id' => array( 'label' => __d( 'partenaire', 'Partenaire.libstruc', true ), 'type' => 'select', 'options' => $options['partenaires'] ),
						'Search.Actioncandidat.id' => array( 'label' => __d( 'actioncandidat', 'Actioncandidat.name', true ), 'type' => 'select', 'options' => $listeactions ),
						'Search.Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
						'Search.Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
						'Search.Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
						'Search.Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.ActioncandidatPersonne.referent_id' => array(  'label' => __d( 'actioncandidat_personne', 'ActioncandidatPersonne.referent_id', true ), 'type' => 'select', 'options' => $options['referents'] ),
						'Search.ActioncandidatPersonne.positionfiche' => array(  'label' => __d( 'actioncandidat_personne', 'ActioncandidatPersonne.positionfiche', true ), 'type' => 'select', 'options' => $options['positionfiche'] ),
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

			<?php echo $xform->input( 'Search.ActioncandidatPersonne.datesignature', array( 'label' => 'Filtrer par date de Fiche de candidature', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$datesignature_from = Set::check( $this->data, 'Search.ActioncandidatPersonne.datesignature_from' ) ? Set::extract( $this->data, 'Search.Actioncandidat.datesignature_from' ) : strtotime( '-1 week' );
					$datesignature_to = Set::check( $this->data, 'Search.ActioncandidatPersonne.datesignature_to' ) ? Set::extract( $this->data, 'Search.Actioncandidat.datesignature_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Search.ActioncandidatPersonne.datesignature_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_from ) );?>
				<?php echo $xform->input( 'Search.ActioncandidatPersonne.datesignature_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_to ) );?>
			</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $xform->end();?>