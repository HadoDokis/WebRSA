<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsuspensioncui66', "Motifssuspensioncuis66::{$this->action}" )
	);

    echo $this->Xform->create();
    echo $this->Default2->subform(
        array(
            'Motifsuspensioncui66.id' => array( 'type' => 'hidden' ),
            'Motifsuspensioncui66.name' => array( 'type' => 'text')
        )
    );

    echo $this->Html->tag(
        'div',
        $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
        .$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
        array( 'class' => 'submit noprint' )
    );

    echo $this->Xform->end();
?>