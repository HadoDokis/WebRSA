<?php
	$this->pageTitle = 'Types de notification PDO';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Typenotifpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typenotifpdo.id', array( 'type' => 'hidden', 'value' => '' ) );
	}
	else {
		echo $form->create( 'Typenotifpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typenotifpdo.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php echo $form->input( 'Typenotifpdo.libelle', array( 'label' => required( __( 'Type de notification', true ) ), 'type' => 'text' ) );?>
	<?php echo $form->input( 'Typenotifpdo.modelenotifpdo', array( 'label' => required( __( 'Modèle de notification', true ) ), 'type' => 'text' ) );?>
</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>

<div class="clearer"><hr /></div>