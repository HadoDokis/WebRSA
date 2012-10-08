<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}" )
	)
?>

<?php
	echo $this->Default->form(
		array(
			'Partenaire.libstruc' => array( 'required' => true ),
			'Partenaire.codepartenaire',
			'Partenaire.numvoie' => array( 'required' => true ),
			'Partenaire.typevoie' => array( 'required' => true ),
			'Partenaire.nomvoie' => array( 'required' => true ),
			'Partenaire.compladr' => array( 'required' => true ),
			'Partenaire.numtel',
			'Partenaire.numfax',
			'Partenaire.email',
			'Partenaire.codepostal' => array( 'required' => true ),
			'Partenaire.ville' => array( 'required' => true )
		),
		array(
			/*'actions' => array( /// FIXME: à faire par christian
				'Partenaire.save',
				'Partenaire.cancel'
			),*/
			'options' => $options
		)
	);
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'partenaires',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>