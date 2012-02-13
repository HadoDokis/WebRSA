<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Type de contrats d\'insertion';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Typocontrat', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	}
	else {
		echo $form->create( 'Typocontrat', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typocontrat.id', array( 'type' => 'hidden' ) );
	}
?>

	<fieldset>
		<?php echo $form->input( 'Typocontrat.lib_typo', array( 'label' => required( __( 'lib_typo', true ) ), 'type' => 'text' ) );?>
	</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
