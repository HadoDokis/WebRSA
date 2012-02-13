<?php

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'actioncandidat_personne', "ActionscandidatsPersonnes::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

?>
<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'ActioncandidatPersonne', array( 'type' => 'post', 'id' => 'actioncandidatpersonneform', 'url' => Router::url( null, true ) ) );
	?>
		<fieldset>
	<legend><?php echo required( $default2->label( 'ActioncandidatPersonne.haspiecejointe' ) );?></legend>

	<?php echo $form->input( 'ActioncandidatPersonne.haspiecejointe', array( 'type' => 'radio', 'options' => $options['ActioncandidatPersonne']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
	<fieldset id="filecontainer-piecejointe" class="noborder invisible">
		<?php
			echo $fileuploader->create(
				$fichiers,
				Router::url( array( 'action' => 'ajaxfileupload' ), true )
			);
		?>
	</fieldset>
</fieldset>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'actioncandidatpersonneform',
			'data[ActioncandidatPersonne][haspiecejointe]',
			$( 'filecontainer-piecejointe' ),
			'1',
			false,
			true
		);
	} );
</script>

<?php
		echo "<h2>Pièces déjà présentes</h2>";
		echo $fileuploader->results( Set::classicExtract( $actioncandidatPersonne, 'Fichiermodule' ) );
	?>
</div>
	<div class="submit">
		<?php
			echo $form->submit( 'Enregistrer', array( 'div'=>false ) );
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>