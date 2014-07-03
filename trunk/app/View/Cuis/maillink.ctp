<?php
	$domain = 'cui';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'cui', "Cuis::{$this->action}" )
	);

	echo $this->Xform->create( null, array('id' => 'Cuimail'));

    echo '<fieldset>';
    if( !empty($mailBodySend) ) {
        echo $this->Xform->fieldValue( 'Textmailcui66.contenuexemple', $mailBodySend, true, 'textarea', array('class' => 'aere') );
    }
    if( empty( $cui['Cui']['dateenvoimail']) ) {
        echo $this->Default2->subform(
            array(
                'Cui.id' => array( 'type' => 'hidden' ),
                'Cui.dateenvoimail' => array( 'required' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'empty' => false )
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
        echo $this->Xform->fieldValue( 'Cui.dateenvoimail', $this->Locale->date( 'Date::short', $cui['Cui']['dateenvoimail'] ), true, 'text', array('class' => 'aere') );
        
        echo $this->Html->tag(
            'div',
             $this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
            array( 'class' => 'submit noprint' )
        );
    }
    echo '</fieldset>';
	echo $this->Xform->end();
	
?>