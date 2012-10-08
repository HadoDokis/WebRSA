<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un CER';
	}
	else {
		$this->pageTitle = 'Modification d\'un CER';
	}
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xform->create();

	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.id' => array( 'type' => 'hidden' ),
			'Contratinsertion.personne_id' => array( 'type' => 'hidden' ),
			'Cer93.id' => array( 'type' => 'hidden' ),
			'Cer93.contratinsertion_id' => array( 'type' => 'hidden' ),
			'Etatcivilcer93.id' => array( 'type' => 'hidden' ),
			'Etatcivilcer93.cer93_id' => array( 'type' => 'hidden' ),
			'Etatcivilcer93.incoherences' => array( 'type' => 'textarea' ),
		)
	);

	echo $this->Xhtml->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array(
			'class' => 'submit noprint'
		)
	);
	echo $this->Xform->end();
?>