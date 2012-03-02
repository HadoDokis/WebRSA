<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'piecetypecourrierpcg66', "Piecestypescourrierspcgs66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default2->subform(
		array(
			'Piecetypecourrierpcg66.id' => array( 'type' => 'hidden' ),
			'Piecetypecourrierpcg66.name' => array( 'required' => true ),
                        'Piecetypecourrierpcg66.typecourrierpcg66_id' => array( 'required' => true, 'type' => 'select', 'options' => $options['Piecetypecourrierpcg66']['typecourrierpcg66_id'], 'empty' => true )
		),
                array(
                    'options' => $options
                )
	);
	echo $xform->end( 'Save' );
	
    echo $default->button(
        'back',
        array(
            'controller' => 'piecestypescourrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>