<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une commission de COV';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une commission de COV';
	}
?>
</h1>

<?php

	echo $form->create( 'Cov58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	echo $default2->subform(
		array(
			'Cov58.id' => array( 'type'=>'hidden' ),
			'Cov58.sitecov58_id' => array( 'type' => 'select', 'empty' => true, 'required' => true ),
			'Cov58.lieu',
			'Cov58.datecommission' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'timeFormat' => __( 'Locale->timeFormat', true ), 'interval' => 15, 'required' => true, 'maxYear' => date('Y') + 1, 'minYear' => date('Y') - 1 ),
			'Cov58.observation' => array( 'type'=>'textarea' )
		)
	);

	echo $form->end( 'Enregistrer' );
?>