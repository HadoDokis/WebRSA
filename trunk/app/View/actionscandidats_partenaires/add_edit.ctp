<?php
	$this->pageTitle = __d( 'actioncandidat_partenaire', "ActionscandidatsPartenaires::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag( 'h1', $this->pageTitle );

	echo $default->form(
		array(
			'ActioncandidatPartenaire.actioncandidat_id' => array( 'type' => 'select', 'empty' => true, 'required' => true ),
			'ActioncandidatPartenaire.partenaire_id' => array( 'type' => 'select', 'empty' => true, 'required' => true )
		),
		array(
			'actions' => array(
				'ActioncandidatPartenaire.save',
				'ActioncandidatPartenaire.cancel'
			),
			'options' => $options
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'actionscandidats_partenaires',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>