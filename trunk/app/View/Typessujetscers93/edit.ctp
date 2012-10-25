<?php
	$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'un type de sujet' : 'Modification d\'un type de sujet' );
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	

	echo $this->Html->tag( 'h1', $title_for_layout );
	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'typesujetcer93' ) ) );

	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Typesujetcer93.id' => array( 'type' => 'hidden' ),
			'Typesujetcer93.name' => array( 'type' => 'text' ),
			'Typesujetcer93.sujetcer93_id' => array( 'type' => 'select', 'options' => $options['Typesujetcer93']['sujetcer93_id'], 'empty' => true )
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
