<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'coderomesecteurdsp66', "Codesromesecteursdsps66::{$this->action}", true )
	);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xform->create( null, array() );

	if (isset($this->data['Coderomesecteurdsp66']['id'])) {
		echo $form->input('Coderomesecteurdsp66.id', array('type'=>'hidden'));
	}

	echo $default->subform(
		array(
			'Coderomesecteurdsp66.code' => array( 'required' => true ),
			'Coderomesecteurdsp66.name' => array( 'required' => true )
		)
	);

	echo $xform->end( __( 'Save', true ) );
	echo $default->button(
		'back',
		array('controller' => 'codesromesecteursdsps66', 'action' => 'index'),
		array('id' => 'Back')
	);
?>