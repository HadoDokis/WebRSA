<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'coderomemetierdsp66', "Codesromemetiersdsps66::{$this->action}", true )
	);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xform->create( null, array() );

	if (isset($this->data['Coderomemetierdsp66']['id'])) {
		echo $form->input('Coderomemetierdsp66.id', array('type'=>'hidden'));
	}

	echo $default->subform(
		array(
			'Coderomemetierdsp66.code' => array( 'required' => true ),
			'Coderomemetierdsp66.name' => array( 'required' => true ),
			'Coderomemetierdsp66.coderomesecteurdsp66_id' => array( 'required' => true, 'options' => $options['Coderomesecteurdsp66'] )
		)
	);

	echo $xform->end( __( 'Save', true ) );
	echo $default->button(
		'back',
		array('controller' => 'codesromemetiersdsps66', 'action' => 'index'),
		array('id' => 'Back')
	);
?>