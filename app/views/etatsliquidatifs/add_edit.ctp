<?php
	$this->pageTitle = 'État liquidatif';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag( 'h1', $this->pageTitle );

	echo $xform->create( 'Etatliquidatif' );
	if( $this->action == 'edit' && isset( $this->data['Etatliquidatif']['id'] ) ) {
		echo $xhtml->tag( 'div', $xform->input( 'Etatliquidatif.id' ) );
	}

	echo $xform->input( 'Etatliquidatif.budgetapre_id', array( 'required' => true, 'options' => $budgetsapres, 'empty' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Etatliquidatif.typeapre', array( 'required' => true, 'options' => $typesapres /* FIXME */, 'empty' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Etatliquidatif.commentaire', array( 'domain' => 'apre', 'required' => true ) );

	echo $xform->submit( 'Enregistrer' );
	echo $xform->end();
?>