<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'modeletypecourrierpcg66', "Modelestypescourrierspcgs66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default2->subform(
		array(
			'Modeletypecourrierpcg66.id' => array( 'type' => 'hidden' ),
			'Modeletypecourrierpcg66.name' => array( 'required' => true ),
			'Modeletypecourrierpcg66.typecourrierpcg66_id' => array( 'required' => true, 'type' => 'select', 'options' => $options['Modeletypecourrierpcg66']['typecourrierpcg66_id'], 'empty' => true ),
			'Modeletypecourrierpcg66.modeleodt' => array( 'required' => true )
		),
                array(
                    'options' => $options
                )
	);
	echo $xform->end( 'Save' );
	
    echo $default->button(
        'back',
        array(
            'controller' => 'modelestypescourrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>