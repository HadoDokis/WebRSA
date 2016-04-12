<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Default3->form(
		array(
			'Communautesr.id' => array( 'type' => 'hidden' ),
			'Communautesr.name',
			'Communautesr.actif' => array( 'empty' => true ),
			'Structurereferente.Structurereferente' => array( 'multiple' => 'checkbox', 'class' => 'divideInto2Collumn' )
		),
		array(
			'options' => $options,
			'buttons' => array( 'Save', 'Cancel' )
		)
	);

	echo $this->Observer->disableFormOnSubmit( Inflector::camelize( "communautesr_{$this->request->params['action']}_form" ) );
?>