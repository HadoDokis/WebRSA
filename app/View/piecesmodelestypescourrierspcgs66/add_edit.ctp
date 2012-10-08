<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'piecemodeletypecourrierpcg66', "Piecesmodelestypescourrierspcgs66::{$this->action}", true )
	);

	echo $xform->create();

	echo $default2->subform(
		array(
			'Piecemodeletypecourrierpcg66.id' => array( 'type' => 'hidden' ),
			'Piecemodeletypecourrierpcg66.name' => array( 'required' => true ),
			'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id' => array( 'required' => true, 'type' => 'select', 'options' => $options['Piecemodeletypecourrierpcg66']['modeletypecourrierpcg66_id'], 'empty' => true ),
			'Piecemodeletypecourrierpcg66.isautrepiece' => array( 'required' => true, 'type' => 'select', 'options' => $options['Piecemodeletypecourrierpcg66']['isautrepiece'], 'empty' => true )
		),
		array(
			'options' => $options
		)
	);
	echo $xform->end( 'Save' );
	
    echo $default->button(
        'back',
        array(
            'controller' => 'piecesmodelestypescourrierspcgs66',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>