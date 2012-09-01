<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'courrierpdo', "Courrierspdos::{$this->action}", true )
	);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $default->form(
		array(
			'Courrierpdo.name' => array( 'type' => 'text' ),
			'Courrierpdo.modeleodt' => array( 'type' => 'text' )
		),
		array(
			'actions' => array(
				'courrierspdos::save',
				'courrierspdos::cancel'
			)
		)
	);
?>