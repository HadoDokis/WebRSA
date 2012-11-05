<?php
	$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'une valeur pour un sous-sujet' : 'Modification d\'une valeur pour un sous-sujet' );
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	

	echo $this->Html->tag( 'h1', $title_for_layout );
	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'valeurparsoussujetcer93' ) ) );

	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Valeurparsoussujetcer93.id' => array( 'type' => 'hidden' ),
			'Valeurparsoussujetcer93.name' => array( 'type' => 'text' ),
			'Valeurparsoussujetcer93.soussujetcer93_id' => array( 'type' => 'select', 'options' => $options['Valeurparsoussujetcer93']['soussujetcer93_id'], 'empty' => true )
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
