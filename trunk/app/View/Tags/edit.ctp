<?php
	echo $this->FormValidator->generateJavascript();
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate' ) );

/***********************************************************************************
 * Choix du formulaire
/***********************************************************************************/
	
	echo '<fieldset>'
		. $this->Default3->subform(
			array(
				'Tag.id' => array( 'type' => 'hidden' ),
				'Tag.valeurtag_id',
				'Tag.commentaire',
			),
			array(
				'paginate' => false,
				'options' => $options
			)
		)
		. '</fieldset>'
	;
	
	echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit();