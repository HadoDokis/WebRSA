<?php
	$this->pageTitle = 'Type d\'actions d\'insertion';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Typeaction', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	}
	else {
		echo $form->create( 'Typeaction', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typeaction.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php echo $form->input( 'Typeaction.libelle', array( 'label' =>  required( __d( 'action', 'Action.lib_action', true ) ), 'type' => 'text' ) );?>
</fieldset>

	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $form->end();?>
