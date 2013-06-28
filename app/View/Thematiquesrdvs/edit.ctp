<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Thematiquerdv.id' => array( 'type' => 'hidden' ),
			'Thematiquerdv.name',
			'Thematiquerdv.typerdv_id' => array( 'empty' => true ),
			'Thematiquerdv.tableliee' => array( 'empty' => true ),
		),
		array(
			'options' => $options,
			'buttons' => array( 'Save', 'Cancel' )
		)
	);
?>