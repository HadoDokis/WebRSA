<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsortie', "Motifssortie::{$this->action}", true )
	);

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $default->form(
		array(
			'Motifsortie.name' => array( 'type' => 'text')
		),
		array(
			'actions' => array(
				'Motifsortie.save',
				'Motifsortie.cancel'
			)
		)
	);
	echo $default->button(
		'back',
		array(
			'controller' => 'motifssortie',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>