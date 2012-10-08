<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<?php
	if( !empty( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Cohortepdo' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $this->Form->create( 'Cohortepdo', array( 'url'=> Router::url( null, true ), 'id' => 'Cohortepdo', 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'CohortepdoTraitement', $( 'CohortepdoTraitementtypepdoId' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'CohortepdoDatedecisionpdo', $( 'CohortepdoDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
	});
</script>
	<fieldset>
		<legend>Recherche par personne</legend>
		<?php echo $this->Form->input( 'Cohortepdo.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
		<?php echo $this->Form->input( 'Cohortepdo.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
		<?php echo $this->Form->input( 'Cohortepdo.matricule', array( 'label' => 'N° CAF', 'type' => 'text', 'maxlength' => 15 ) );?>
		<?php echo $this->Form->input( 'Cohortepdo.numdemrsa', array( 'label' => 'N° demande RSA', 'type' => 'text', 'maxlength' => 11 ) );?>
		<?php
			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
	</fieldset>
<fieldset class= "noprint">
		<legend>Recherche PDO</legend>
		<?php if( $this->action == 'avisdemande' ):?>
			<?php echo $this->Form->input( 'Cohortepdo.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
			<?php
				if( Configure::read( 'CG.cantons' ) ) {
					echo $this->Form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
			?>
		<?php else :?>
		<?php echo $this->Form->input( 'Cohortepdo.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $this->Form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
		<?php
			echo $this->Form->input( 'Cohortepdo.typepdo_id', array( 'label' =>  ( __d( 'propopdo', 'Propopdo.typepdo_id' ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
			echo $this->Form->input( 'Cohortepdo.decisionpdo_id', array( 'label' =>  ( __( 'Décision du Conseil Général' ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
			echo $this->Form->input( 'Cohortepdo.motifpdo', array( 'label' => __d( 'propopdo', 'Propopdo.motifpdo' ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );
			echo $this->Form->input( 'Cohortepdo.user_id', array( 'label' => __d( 'propopdo', 'Propopdo.user_id' ), 'type' => 'select', 'options' => $gestionnaire, 'empty' => true ) );
		?>
			<?php echo $this->Form->input( 'Cohortepdo.datedecisionpdo', array( 'label' => 'Filtrer par date de décision des PDOs', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de saisie de la PDO</legend>
				<?php
					$datedecisionpdo_from = Set::check( $this->request->data, 'Cohortepdo.datedecisionpdo_from' ) ? Set::extract( $this->request->data, 'Cohortepdo.datedecisionpdo_from' ) : strtotime( '-1 week' );
					$datedecisionpdo_to = Set::check( $this->request->data, 'Cohortepdo.datedecisionpdo_to' ) ? Set::extract( $this->request->data, 'Cohortepdo.datedecisionpdo_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Cohortepdo.datedecisionpdo_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_from ) );?>
				<?php echo $this->Form->input( 'Cohortepdo.datedecisionpdo_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_to ) );?>
			</fieldset>
		<?php endif;?>
		<?php echo $this->Form->input( 'Cohortepdo.traitement', array( 'label' => 'Filtrer par traitement des PDOs', 'type' => 'checkbox', 'div' => false ) );?>
		<fieldset class= "noprint">
			<legend>Traitement des PDOs</legend>
				<?php echo $this->Form->input( 'Cohortepdo.traitementtypepdo_id', array( 'label' =>  ( __( 'Traitement' ) ), 'type' => 'select', 'options' => $traitementtypepdo, 'empty' => true ) ); ?>
				<?php $daterevision = Set::check( $this->request->data, 'Cohortepdo.daterevision' ) ? Set::extract( $this->request->data, 'Cohortepdo.daterevision' ) : strtotime( '+1 week' ); ?>
				<?php echo $this->Form->input( 'Cohortepdo.daterevision', array( 'label' => 'Date de révision inférieure à ', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date('Y')+2, 'minYear' => date('Y')-2, 'selected' => $daterevision ) ); ?>
		</fieldset>
		<?php
			if( $this->action == 'avisdemande' ) {
				echo $this->Search->etatdosrsa($etatdosrsa);
			}
		?>
	</fieldset>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Form->end();?>