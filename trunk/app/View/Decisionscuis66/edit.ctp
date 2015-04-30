<?php
	echo $this->FormValidator->generateJavascript();
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
	
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'id' => 'Decisioncui66AddEditForm', 'class' => 'Cui66AddEdit' ) );

/***********************************************************************************
 * Formulaire Décision
/***********************************************************************************/
	
	echo '<fieldset><legend>' . __d('propositionscuis66', 'Propositioncui66.formulaire') . '</legend>'
		. $this->Default3->index(
			$results,
			array(
				'Propositioncui66.donneuravis',
				'Propositioncui66.dateproposition',
				'Propositioncui66.avis',
				'Propositioncui66.observation',
			),
			array(
				'options' => $options,
				'paginate' => false,
				'domain' => 'propositionscuis66'
			)
		) . '</fieldset>'
	;
	
	echo '<fieldset><legend>' . __d('decisionscuis66', 'Decisioncui66.formulaire') . '</legend>'
		. $this->Default3->subform(
			array(
				'Decisioncui66.id' => array( 'type' => 'hidden' ),
				'Decisioncui66.cui66_id' => array( 'type' => 'hidden' ),
				'Decisioncui66.decision',
				'Decisioncui66.datedecision' => array( 'dateFormat' => 'DMY', 'timeFormat' => 24, 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
				'Decisioncui66.observation',
			) ,
			array( 'options' => $options )
		)
		. '</fieldset>'
	;

	echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit( 'Decisioncui66AddEditForm' );
	