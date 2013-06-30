<?php
	// FIXME: quand c'est de la visualisation, on montre le formulaire mais l'intitulé sera différent
	// TODO: cacher le formulaire
	// TODO: ajouter version pour l'index
	if( $this->action == 'view' ) {
		echo $this->Default3->titleForLayout( $tableausuivipdv93 );
	}
	else {
		echo $this->Default3->titleForLayout();
	}
	$domain = Inflector::tableize( Inflector::classify( $this->request->params['controller'] ) );

	if( $this->action == 'view' ) {
		$created = $this->Locale->date( __( 'Locale->datetime' ), $tableausuivipdv93['Tableausuivipdv93']['created'] );
		$modified = $this->Locale->date( __( 'Locale->datetime' ), $tableausuivipdv93['Tableausuivipdv93']['modified'] );

		if( $created == $modified ) {
			$h2 = "Photographie du {$created}";
		}
		else {
			$h2 = "Photographie entre le {$created} et le {$modified}";
		}

		echo $this->Html->tag( 'h2', $h2 );
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	$params_rdv_structurereferente = array(
		'type' => (
			in_array( $this->request->params['action'], array( 'tableau1b3', 'index', 'tableaud1' ) )
			|| ( $this->request->params['action'] == 'view' && $tableausuivipdv93['Tableausuivipdv93']['name'] == 'tableaud1' )
			? 'hidden'
			: 'checkbox'
		)
	);

	if( $this->request->params['action'] == 'tableau1b6' || ( $this->request->params['action'] == 'view' && $tableausuivipdv93['Tableausuivipdv93']['name'] == 'tableau1b6' ) ) {
		$params_rdv_structurereferente['label'] = 'Dont le bénéficiaire possède au moins un rendez-vous honoré dans le PDV';
	}

	echo $this->Default3->form(
		array(
			'Search.annee' => array( 'empty' => ( ( $this->action == 'index' ) ? true : false ) ),
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $userIsCg ? 'select' : 'hidden' ) ),
			'Search.user_id' => array( 'empty' => true, 'type' => ( ( $this->action == 'index' ) ? 'select' : 'hidden' ) ),
			'Search.tableau' => array( 'empty' => true, 'type' => ( $this->request->params['action'] == 'index' ? 'select' : 'hidden' ) ),
			'Search.rdv_structurereferente' => $params_rdv_structurereferente,
			'Search.dsps_maj_dans_annee' => array( 'type' => ( $this->request->params['action'] == 'tableau1b3' ? 'checkbox' : 'hidden' ) ),
		),
		array(
			'options' => $options,
			'buttons' => ( $this->action == 'view' ? false : array( 'Search' ) )
		)
	);
?>