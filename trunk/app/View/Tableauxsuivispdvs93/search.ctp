<?php
	// TODO: mettre en commun pour les différents tableaux
	// FIXME: quand c'est de la visualisation, on montre le formulaire mais l'intitulé sera différent, et ne pas permettre d'envoyer le formulaire ?
	// TODO: cacher le formulaire
	// TODO: ajouter version pour l'index
	if( $this->action == 'view' ) {
		echo $this->Default3->titleForLayout( $tableausuivipdv93 );
	}
	else {
		echo $this->Default3->titleForLayout();
	}
	$domain = Inflector::tableize( Inflector::classify( $this->request->params['controller'] ) );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Search.annee' => array( 'empty' => ( ( $this->action == 'index' ) ? true : false ) ),
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $userIsCg ? 'select' : 'hidden' ) ),
			'Search.user_id' => array( 'empty' => true, 'type' => ( ( $this->action == 'index' ) ? 'select' : 'hidden' ) ),
			'Search.rdv_structurereferente' => array( 'type' => ( in_array( $this->request->params['action'], array( 'tableau1b3', 'index' ) ) ? 'hidden' : 'checkbox' ) ),
			'Search.dsps_maj_dans_annee' => array( 'type' => ( $this->request->params['action'] == 'tableau1b3' ? 'checkbox' : 'hidden' ) ),
		),
		array(
			'options' => $options,
			'buttons' => ( $this->action == 'view' ? false : array( 'Search' ) )
		)
	);
?>