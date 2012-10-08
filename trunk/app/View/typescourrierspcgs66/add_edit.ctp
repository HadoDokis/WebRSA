<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typecourrierpcg66', "Typescourrierspcgs66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default2->subform(
		array(
			'Typecourrierpcg66.id' => array( 'type' => 'hidden' ),
			'Typecourrierpcg66.name' => array( 'required' => true )
		)
	);

	echo $xform->end( 'Save' );
	
    echo $default->button(
        'back',
        array(
            'controller' => 'typescourrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>