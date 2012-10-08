<?php
	$this->pageTitle = 'Compositions de foyer';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>

<fieldset>
	<?php
		echo $form->create( 'Compofoyerpcg66', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

		echo $default2->subform(
			array(
				'Compofoyerpcg66.id' => array( 'type'=>'hidden' ),
				'Compofoyerpcg66.name' => array( 'required' => true )
			)
		);
	?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
</fieldset>