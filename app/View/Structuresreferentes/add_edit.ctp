<?php
	$this->pageTitle = 'Structures référentes';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $this->Form->create( 'Structurereferente', array( 'type' => 'post' ) );
		echo '<div>';
		echo $this->Form->input( 'Structurereferente.id', array( 'type' => 'hidden' ) );
		echo $this->Form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
	else {
		echo $this->Form->create( 'Structurereferente', array( 'type' => 'post' ) );
		echo '<div>';
		echo $this->Form->input( 'Structurereferente.id', array( 'type' => 'hidden' ) );
		echo $this->Form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
?>

<fieldset>
	<?php echo $this->Form->input( 'Structurereferente.lib_struc', array( 'label' => required( __d( 'structurereferente', 'Structurereferente.lib_struc' ) ), 'type' => 'text' ) );?>
	<?php echo $this->Form->input( 'Structurereferente.num_voie', array( 'label' => required( __( 'num_voie' ) ), 'type' => 'text', 'maxlength' => 15 ) );?>
	<?php echo $this->Form->input( 'Structurereferente.type_voie', array( 'label' => required( __( 'type_voie' ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
	<?php echo $this->Form->input( 'Structurereferente.nom_voie', array( 'label' => required(  __( 'nom_voie' ) ), 'type' => 'text', 'maxlength' => 50 ) );?>
	<?php echo $this->Form->input( 'Structurereferente.code_postal', array( 'label' => required( __( 'code_postal' ) ), 'type' => 'text', 'maxlength' => 5 ) );?>
	<?php echo $this->Form->input( 'Structurereferente.ville', array( 'label' => required( __( 'ville' ) ), 'type' => 'text' ) );?>
	<?php echo $this->Form->input( 'Structurereferente.code_insee', array( 'label' => required( __( 'code_insee' ) ), 'type' => 'text', 'maxlength' => 5 ) );?>
	<?php echo $this->Form->input( 'Structurereferente.numtel', array( 'label' => __( 'numtel' ), 'type' => 'text', 'maxlength' => 19 ) );?>
</fieldset>
<div><?php echo $this->Form->input( 'Structurereferente.filtre_zone_geo', array( 'label' => 'Restreindre les zones géographiques', 'type' => 'checkbox' ) );?></div>


<script type="text/javascript">
	function toutCocherZonesgeographiques() {
		return toutCocher( 'input[name="data[Zonegeographique][Zonegeographique][]"]' );
	}
	function toutDecocherZonesgeographiques() {
		return toutDecocher( 'input[name="data[Zonegeographique][Zonegeographique][]"]' );
	}
</script>
<fieldset class="col2" id="filtres_zone_geo">
	<legend>Zones géographiques</legend>
	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			observeDisableFieldsetOnCheckbox( 'StructurereferenteFiltreZoneGeo', 'filtres_zone_geo', false );
		} );
	</script>
	<?php echo $this->Form->button( 'Tout cocher', array( 'onclick' => "return toutCocherZonesgeographiques();" ) );?>
	<?php echo $this->Form->button( 'Tout décocher', array( 'onclick' => "return toutDecocherZonesgeographiques();" ) );?>

	<?php echo $this->Form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>

<fieldset class="col2">
	<legend><?php echo required( 'Types d\'orientations' ); ?></legend>
	<?php echo $this->Xform->input( 'Structurereferente.typeorient_id', array( 'label' => false, 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
</fieldset>

<fieldset class="col2">
	<legend><?php echo required( 'Gère les CERs ?' );?></legend>
	<?php echo $this->Xform->enum( 'Structurereferente.contratengagement', array(  'legend' => false, 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Structurereferente']['contratengagement'] ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo required( 'Gère les APREs ?' );?></legend>
	<?php echo $this->Xform->enum( 'Structurereferente.apre', array(  'legend' => false, 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Structurereferente']['apre'] ) );?>
</fieldset>

<fieldset class="col2">
	<legend><?php echo 'Gère les Orientations ?';?></legend>
	<?php echo $this->Xform->enum( 'Structurereferente.orientation', array(  'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Structurereferente']['orientation'] ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo 'Gère les PDOs ?';?></legend>
	<?php echo $this->Xform->enum( 'Structurereferente.pdo', array(  'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Structurereferente']['pdo'] ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo 'Active ?';?></legend>
	<?php echo $this->Form->input( 'Structurereferente.actif', array( 'legend' => false, 'type' => 'radio', 'options' => $options['Structurereferente']['actif'] ) ); ?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo 'Type de structure';?></legend>
	<?php echo $this->Form->input( 'Structurereferente.typestructure', array( 'legend' => false, 'type' => 'radio', 'options' => $options['Structurereferente']['typestructure'] ) ); ?>
</fieldset>

	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $this->Form->end();?>