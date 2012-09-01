<?php
	$this->pageTitle = 'Tiers prestataire APRE';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $xform->create( 'Tiersprestataireapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		}
		else {
			echo $xform->create( 'Tiersprestataireapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $xform->input( 'Tiersprestataireapre.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
	?>

	<fieldset>
		<?php
			echo $xform->input( 'Tiersprestataireapre.nomtiers', array( 'required' => true, 'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.siret', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.numvoie', array( 'domain' => 'apre' ) );
			echo $xform->enum( 'Tiersprestataireapre.typevoie', array(  'domain' => 'apre', 'options' => $typevoie, 'empty' => true ) );
			echo $xform->input( 'Tiersprestataireapre.nomvoie', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.compladr', array( 'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.codepos', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.ville', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.canton', array( 'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.numtel', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.adrelec', array( 'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.nomtiturib', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.etaban', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.guiban', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.numcomptban', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.nometaban', array(  'domain' => 'apre' ) );
			echo $xform->input( 'Tiersprestataireapre.clerib', array(  'domain' => 'apre', 'maxlength' => 2 ) );
		?>
	</fieldset>

	<fieldset>
		<legend>Formations liées</legend>
			<?php
				echo $xform->enum( 'Tiersprestataireapre.aidesliees', array( 'required' => true, 'domain' => 'apre', 'options' => $natureAidesApres, 'empty' => true ) );
			?>
	</fieldset>

		<div class="submit">
			<?php
				echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
				echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
			?>
		</div>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>