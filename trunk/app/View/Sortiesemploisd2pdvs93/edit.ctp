<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Sortieemploid2pdv93.id' => array( 'type' => 'hidden' ),
			'Sortieemploid2pdv93.name',
		),
		array(
			'buttons' => array( 'Save', 'Cancel' )
		)
	);
?>