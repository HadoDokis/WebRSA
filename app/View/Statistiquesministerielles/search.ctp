<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag( 'h1', $title_for_layout );

	$formId = 'StatistiqueministerielleSearchForm';

	echo $this->Default3->actions(
		array(
			"/{$this->request->params['controller']}/{$this->request->params['action']}/#toggleform" => array(
				'onclick' => "\$('{$formId}').toggle(); return false;",
				'text' => 'Formulaire'
			),
		)
	);

	echo $this->Xform->create( null, array( 'type' => 'post', 'id' => $formId, 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );
	echo $this->Search->blocAdresse( $mesCodesInsee, $cantons, null, false );
	echo $this->Xform->input( 'Search.serviceinstructeur', array( 'type' => 'select', 'options' => $servicesinstructeurs, 'empty' => true, 'label' => 'Service instructeur' ) );
	echo $this->Xform->input( 'Search.annee', array( 'type' => 'select', 'options' => array_combine( range( date( 'Y' ), 2009, -1 ), range( date( 'Y' ), 2009, -1 ) ), 'label' => 'Année' ) );
	echo $this->Xhtml->tag(
		'div',
		$this->Xform->submit( 'Rechercher', array( 'div' => false, 'type' => 'submit' ) )
		.' '.$this->Xform->submit( 'Réinitialiser', array( 'div' => false, 'type'=>'reset' ) ),
		array( 'class' => 'submit noprint' )
	);
	echo $this->Xform->end();

	echo $this->Observer->disableFormOnSubmit( $formId );
?>