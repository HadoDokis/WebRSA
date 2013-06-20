<?php
	// TODO: mettre en commun pour les différents tableaux
	echo $this->Default3->titleForLayout();
	$domain = Inflector::tableize( Inflector::classify( $this->request->params['controller'] ) );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Search.annee',
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $userIsCg ? 'select' : 'hidden' ) ),
			'Search.dsps_maj_dans_annee' => array( 'type' => ( $this->request->params['action'] == 'tableau1b3' ? 'checkbox' : 'hidden' ) ),
		),
		array(
			'options' => $options,
			'buttons' => 'Search'
		)
	);
?>