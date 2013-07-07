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

	if( in_array( $this->request->params['action'], array( 'historiser', 'view' ) ) ) {
		$tableau = $this->request->pass[0];
	}
	else {
		$tableau = $this->request->params['action'];
	}

	$params_rdv_structurereferente = array(
		'type' => (
			in_array( $tableau, array( 'tableau1b3', 'index', 'tableaud1' ) )
			? 'hidden'
			: 'checkbox'
		)
	);

	$params_dsps_maj_dans_annee = array(
		'type' => (
			in_array( $tableau, array( 'tableau1b3' ) )
			? 'checkbox'
			: 'hidden'
		)
	);

	$params_soumis_dd_dans_annee = array(
		'type' => (
			in_array( $tableau, array( 'tableaud1' ) )
			? 'checkbox'
			: 'hidden'
		)
	);

	if( $this->request->params['action'] == 'tableau1b6' || ( $this->request->params['action'] == 'view' && $tableausuivipdv93['Tableausuivipdv93']['name'] == 'tableau1b6' ) ) {
		$params_rdv_structurereferente['label'] = 'Dont le bénéficiaire possède au moins un rendez-vous honoré dans le PDV';
	}

	echo $this->Default3->form(
		array(
			'Search.annee' => array( 'empty' => ( $tableau == 'index' ? true : false ) ),
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $userIsCg ? 'select' : 'hidden' ) ),
			'Search.user_id' => array( 'empty' => true, 'type' => ( $tableau == 'index' ? 'select' : 'hidden' ) ),
			'Search.tableau' => array( 'empty' => true, 'type' => ( $tableau == 'index' ? 'select' : 'hidden' ) ),
			'Search.rdv_structurereferente' => $params_rdv_structurereferente,
			'Search.dsps_maj_dans_annee' => $params_dsps_maj_dans_annee,
			'Search.soumis_dd_dans_annee' => $params_soumis_dd_dans_annee,
		),
		array(
			'options' => $options,
			'buttons' => ( in_array( $this->action, array( 'view', 'historiser' ) ) ? false : array( 'Search' ) )
		)
	);
?>