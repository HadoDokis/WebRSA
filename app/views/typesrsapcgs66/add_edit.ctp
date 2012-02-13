<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typersapcg66', "Typesrsapcgs66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default2->subform(
		array(
			'Typersapcg66.id' => array( 'type' => 'hidden' ),
			'Typersapcg66.name' => array( 'required' => true )
		)
	);

	echo $xform->end( 'Save' );
	
    echo $default->button(
        'back',
        array(
            'controller' => 'typesrsapcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>