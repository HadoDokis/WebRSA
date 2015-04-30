<?php
	echo $this->FormValidator->generateJavascript();
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
		
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'id' => 'Suspensioncui66AddEditForm', 'class' => 'Cui66AddEdit' ) );

/***********************************************************************************
 * Formulaire Suspension
/***********************************************************************************/
	
	echo '<fieldset><legend>' . __d('suspensionscuis66', 'Suspensioncui66.formulaire') . '</legend>'
		. $this->Default3->subform(
			array(
				'Suspensioncui66.id' => array( 'type' => 'hidden' ),
				'Suspensioncui66.cui66_id' => array( 'type' => 'hidden' ),
				'Suspensioncui66.observation',
				'Suspensioncui66.duree',
				'Suspensioncui66.datedebut' => array( 'dateFormat' => 'DMY' ),
				'Suspensioncui66.datefin' => array( 'dateFormat' => 'DMY' ),
				'Suspensioncui66.motif' => array( 'type' => 'select' ),
				
			) ,
			array( 'options' => $options )
		)
		. '</fieldset>'
	;

	echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit( 'Suspensioncui66AddEditForm' );
	