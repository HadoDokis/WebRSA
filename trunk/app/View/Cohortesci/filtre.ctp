<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	if( Configure::read( 'Cg.departement') == 66 ){
		$complexeparticulier = 'C';
	}
	else{
		$complexeparticulier = 'S';
	}

	?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsOnValue( 'ContratinsertionDecisionCi', [ 'ContratinsertionDatevalidationCiDay', 'ContratinsertionDatevalidationCiMonth', 'ContratinsertionDatevalidationCiYear' ], 'V', false );
	});
</script>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'FiltreCreated', $( 'FiltreCreatedFromDay' ).up( 'fieldset' ), false );
	});
</script>


<?php
	if( is_array( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
		).'</li></ul>';
	}
?>

<?php echo $this->Form->create( 'Filtre', array( 'id' => 'Filtre', 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Search->blocAllocataire();
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>
	<fieldset>
		<legend>Recherche de CER</legend>
			<?php echo $this->Form->input( 'Filtre.created', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de saisie du contrat</legend>
				<?php
					$created_from = Set::check( $this->request->data, 'Filtre.created_from' ) ? Set::extract( $this->request->data, 'Filtre.created_from' ) : strtotime( '-1 week' );
					$created_to = Set::check( $this->request->data, 'Filtre.created_to' ) ? Set::extract( $this->request->data, 'Filtre.created_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Filtre.created_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $created_from ) );?>
				<?php echo $this->Form->input( 'Filtre.created_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $created_to ) );?>
			</fieldset>

			<?php echo $this->Form->input( 'Filtre.structurereferente_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_struct' ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
			<?php echo $this->Form->input( 'Filtre.referent_id', array( 'label' => __( 'Nom du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
			<?php echo $this->Ajax->observeField( 'FiltreStructurereferenteId', array( 'update' => 'FiltreReferentId', 'url' => array( 'action' => 'ajaxreferent' ) ) );?>
			<?php
				if( $this->action == 'valides' ) {
					echo $this->Form->input( 'Filtre.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) );
					echo $this->Form->input( 'Filtre.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );
				}
			?>
			<?php
				if( Configure::read( 'Cg.departement' ) != 66 ){
					echo $this->Form->input( 'Filtre.forme_ci', array( 'div' => false, 'type' => 'radio', 'options' => $forme_ci, 'legend' => 'Forme du contrat', 'default' => $complexeparticulier ) );
				}
				else if( $this->action == 'valides' && Configure::read( 'Cg.departement' ) == 66 ){
					echo $this->Form->input( 'Filtre.forme_ci', array( 'div' => false, 'type' => 'radio', 'options' => $forme_ci, 'legend' => 'Forme du contrat' ) );
				}
			?>

	</fieldset>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Form->end();?>