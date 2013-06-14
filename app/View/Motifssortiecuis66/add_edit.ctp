<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsortiecui66', "Motifssortiecuis66::{$this->action}" )
	);

	echo $this->Default->form(
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
	echo $this->Default->button(
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