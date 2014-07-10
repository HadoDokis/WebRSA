<?php
	$domain = 'decisioncui66';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'decisioncui66', "Decisionscuis66::{$this->action}" )
	);

	echo $this->Xform->create( null, array('id' => 'Decisioncui66mail'));

    echo '<fieldset>';
    if( !empty($mailBodySend) ) {
        echo $this->Xform->fieldValue( 'Textmailcui66.contenuexemple', $mailBodySend, true, 'textarea', array('class' => 'aere') );
    }
    if( empty( $cui['Cui']['dateenvoimail']) ) {
        echo $this->Default2->subform(
            array(
                'Decisioncui66.id' => array( 'type' => 'hidden' ),
                'Decisioncui66.dateenvoimail' => array( 'required' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'empty' => false )
            )
        );
   


        echo $this->Html->tag(
            'div',
             $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
            .$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
            array( 'class' => 'submit noprint' )
        );
    }
    else {
        echo $this->Xform->fieldValue( 'Decisioncui66.dateenvoimail', $this->Locale->date( 'Date::short', $cui['Decisioncui66']['dateenvoimail'] ), true, 'text', array('class' => 'aere') );
        
        echo $this->Html->tag(
            'div',
             $this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
            array( 'class' => 'submit noprint' )
        );
    }
    echo '</fieldset>';
	echo $this->Xform->end();
	
?>