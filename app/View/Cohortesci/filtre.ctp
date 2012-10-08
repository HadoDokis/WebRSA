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
		observeDisableFieldsetOnCheckbox( 'FiltreDateSaisiCi', $( 'FiltreDateSaisiCiFromDay' ).up( 'fieldset' ), false );
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

<?php echo $this->Form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );?>
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
			<?php echo $this->Form->input( 'Filtre.date_saisi_ci', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de saisie du contrat</legend>
				<?php
					$date_saisi_ci_from = Set::check( $this->request->data, 'Filtre.date_saisi_ci_from' ) ? Set::extract( $this->request->data, 'Filtre.date_saisi_ci_from' ) : strtotime( '-1 week' );
					$date_saisi_ci_to = Set::check( $this->request->data, 'Filtre.date_saisi_ci_to' ) ? Set::extract( $this->request->data, 'Filtre.date_saisi_ci_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Filtre.date_saisi_ci_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_from ) );?>
				<?php echo $this->Form->input( 'Filtre.date_saisi_ci_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_to ) );?>
			</fieldset>

			<?php echo $this->Form->input( 'Filtre.structurereferente_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_struct' ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
			<?php echo $this->Form->input( 'Filtre.referent_id', array( 'label' => __( 'Nom du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
			<?php echo $this->Ajax->observeField( 'FiltreStructurereferenteId', array( 'update' => 'FiltreReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );?>
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