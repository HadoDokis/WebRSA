<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Statut de rendez-vous';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Statutrdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Statutrdv.id', array( 'type' => 'hidden' ) );
	}
	else {
		echo $form->create( 'Statutrdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Statutrdv.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php
		echo $form->input( 'Statutrdv.libelle', array( 'label' =>  required( __( 'Statut du RDV', true ) ), 'type' => 'text' ) );
		if ( Configure::read( 'Cg.departement' ) == 58 ) {
			echo $form->input( 'Statutrdv.provoquepassageep', array( 'legend' =>  required( __( 'Provoque un passage en EP ?', true ) ), 'fieldset' => false, 'type' => 'radio', 'options' => $provoquepassageep ) );
		}
		elseif ( Configure::read( 'Cg.departement' ) == 66 ) {
			echo $form->input( 'Statutrdv.permetpassageepl', array( 'legend' =>  required( __( 'Permet un passage en EPL Audition ?', true ) ), 'fieldset' => false, 'type' => 'radio', 'options' => $permetpassageepl ) );
		}
	?>
</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'statutsrdvs',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>