<?php
	$this->pageTitle = 'Actions d\'insertion';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>

<?php
	echo $form->create( 'Action', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	if( $this->action == 'edit' ) {
		echo $form->input( 'Action.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php echo $form->input( 'Action.code', array( 'label' =>  required( __d( 'action', 'Action.code_action', true ) ), 'type' => 'text', 'maxlength' => 2 ) );?>
	<?php echo $form->input( 'Action.libelle', array( 'label' =>  required( __d( 'action', 'Action.lib_action', true ) ), 'type' => 'text' ) );?>
	<?php echo $form->input( 'Action.typeaction_id', array( 'label' =>  required( __d( 'action', 'Action.type_action', true ) ), 'type' => 'select', 'options' => $libtypaction, 'empty' => true ) );?>
</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>