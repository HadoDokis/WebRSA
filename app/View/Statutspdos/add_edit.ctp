<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'statutpdo', "Statutspdos::{$this->action}" )
	)
?>
<?php
	echo $this->Default->form(
		array(
			'Statutpdo.libelle' => array( 'type' => 'text' )
		),
		array(
			'actions' => array(
				'Statutpdo.save',
				'Statutpdo.cancel'
			)
		)
	);
?>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'statutspdos',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
