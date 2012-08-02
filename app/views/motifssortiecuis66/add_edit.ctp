<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsortiecui66', "Motifssortiecuis66::{$this->action}", true )
	);

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $default->form(
		array(
			'Motifsortiecui66.name' => array( 'type' => 'text')
		),
		array(
			'actions' => array(
				'Motifsortiecui66.save',
				'Motifsortiecui66.cancel'
			)
		)
	);
	echo $default->button(
		'back',
		array(
			'controller' => 'motifssortiecuis66',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>