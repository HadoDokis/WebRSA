<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1><?php echo $this->pageTitle = 'Suppression de la commission d\'EP'; ?></h1>

<?php
	echo $form->create( 'Commissionep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	
	echo $form->input( 'Commissionep.raisonannulation', array( 'type' => 'textarea', 'label' => 'Raison de l\'annulation de la commission d\'EP' ) );
	
	echo $form->end( 'Confirmer' );
	
	echo $default->button(
		'back',
		array(
			'controller' => 'commissionseps',
			'action'     => 'view',
			$commissionep_id
		),
		array(
			'id' => 'Back'
		)
	);
?>