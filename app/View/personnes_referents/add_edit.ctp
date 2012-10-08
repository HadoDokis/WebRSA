<?php
	$this->pageTitle = 'Référents liés à la personne';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		dependantSelect( 'PersonneReferentReferentId', 'PersonneReferentStructurereferenteId' );
	} );
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
<?php
	echo $xform->create( 'PersonneReferent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	if( $this->action == 'edit' ) {
		echo '<div>'.$xform->input( 'PersonneReferent.id', array( 'type' => 'hidden' ) ).'</div>';
	}
?>

	<fieldset>
		<legend>Structures référentes</legend>
		<?php
			echo $xform->input( 'PersonneReferent.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

			echo $xform->input( 'PersonneReferent.structurereferente_id', array( 'label' => required( 'Structure référente' ), 'type' => 'select' , 'options' => $options['structuresreferentes'], 'empty' => true ) );
			echo $xform->input( 'PersonneReferent.referent_id', array( 'label' => required( 'Référents' ), 'type' => 'select' , 'options' => $options['referents'], 'empty' => true ) );

			echo $xform->input( 'PersonneReferent.dddesignation', array( 'label' => required( 'Début de désignation' ), 'type' => 'date' , 'dateFormat' => 'DMY' ) );

		?>
	</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>