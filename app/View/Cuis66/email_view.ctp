<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
		
	echo '<div class="Cui66AddEdit">';

/***********************************************************************************
 * Formulaire E-mail
/***********************************************************************************/
	
	echo '<fieldset><legend id="Cui66Choixformulaire">' . __d('cuis66', 'Emailcui.entete_email') . '</legend>'
		. $this->Default3->subformView(
			array(
				'Emailcui.emailredacteur',
				'Emailcui.emailemployeur',
				'Emailcui.insertiondate' => array( 'dateFormat' => 'DMY', 'type' => 'date', 'view' => true ),
				'Emailcui.commentaire' => array ( 'type' => 'textarea' )
			) ,
			array( 'options' => $options )
		)			
		. '</fieldset><fieldset><legend>' . __d('cuis66', 'Emailcui.email') . '</legend>'
		. $this->Default3->subform( array( 'Emailcui.titre' => array( 'view' => true ) ) )
			
		. '<div class="input value">' .  __d( 'cuis66', 'Emailcui.message' )
		. '<hr>'
		. preg_replace('/[\n\r]{2,2}/', '<br />', $this->request->data['Emailcui']['message']) . '</div><hr>'
		. $this->Default3->subform(
			array( 'Emailcui.pj' => array( 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Piecemailcui66'] ) ),
			array( 'options' => $options )
		) . '</fieldset>'
	;
	
	echo '<br />' . $this->Default->button(
		'back',
		array(
			'controller' => 'cuis66',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back',
			'class' => 'aere'
		)
	);
	
	echo '</div>';
?>