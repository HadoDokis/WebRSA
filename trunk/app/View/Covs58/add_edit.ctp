<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une COV';
	}
	else {
		$this->pageTitle = 'Modification d\'une COV';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	echo $this->Form->create( 'Cov58', array( 'type' => 'post' ) );

	echo $this->Default2->subform(
		array(
			'Cov58.id' => array( 'type'=>'hidden' ),
			'Cov58.sitecov58_id' => array( 'type' => 'select', 'empty' => true, 'required' => true ),
// 			'Cov58.lieu',
			'Cov58.datecommission' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'timeFormat' => __( 'Locale->timeFormat' ), 'interval' => 15, 'required' => true, 'maxYear' => date('Y') + 1, 'minYear' => date('Y') - 1 ),
			'Cov58.observation' => array( 'type'=>'textarea' )
		)
	);
	if( $this->action == 'edit' ){
		echo $this->Default2->subform( array( 'Cov58.etatcov' => array( 'type'=>'hidden' ) ) );
	}

	echo $this->Form->end( 'Enregistrer' );
?>