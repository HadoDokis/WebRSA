<?php
	$this->pageTitle = 'Gestion des doublons complexes';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	echo $this->Default3->actions(
		array(
			'/Gestionsdoublons/index/#toggleform' => array(
				'onclick' => '$(\'GestionsdoublonsIndexForm\').toggle(); return false;'
			),
		)
	);

	if( ( isset( $this->request->data['Search'] ) && !empty( $this->request->params['named'] ) ) ) {
		$out = "document.observe( 'dom:loaded', function() { \$('GestionsdoublonsIndexForm').hide(); } );";
		echo $this->Html->scriptBlock( $out );
	}

	// Moteur de recherche
	echo $this->Xform->create( null, array( 'id' => 'GestionsdoublonsIndexForm' ) );

	// Filtres concernant le dossier
	echo $this->Search->blocDossier( $options['Situationdossierrsa']['etatdosrsa'], 'Search' );

	echo $this->Search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );

	// Filtres concernant l'allocataire
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'search_plugin', 'Search.Personne' ) );
	echo $this->Search->blocAllocataire( array(), array(), 'Search' );
	echo $this->Search->toppersdrodevorsa( $options['Calculdroitrsa']['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );
	echo '</fieldset>';

	echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours, 'Search' );
	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );
	echo $this->Search->observeDisableFormOnSubmit( 'CohortesDossier2pdvs93IndexForm' );

	echo $this->Xform->end( 'Search' );

	// Résultats
	if( isset( $results ) ) {
		$index = $this->Default3->index(
			$results,
			array(
				'Dossier.numdemrsa',
				'Dossier.dtdemrsa',
				'Dossier.matricule',
				'Demandeur.nom',
				'Demandeur.prenom',
				'Situationdossierrsa.etatdosrsa',
				'Adresse.locaadr',
				'Dossier.locked' => array( 'type' => 'boolean' ),
				'Dossier2.numdemrsa',
				'Dossier2.dtdemrsa' => array( 'type' => 'date' ),
				'Dossier2.matricule',
				'Demandeur2.nom',
				'Demandeur2.prenom',
				'Situationdossierrsa2.etatdosrsa',
				'Adresse2.locaadr',
				'Dossier2.locked' => array( 'type' => 'boolean' ),
				'/Personnes/index/#Foyer.id#' => array(
					'disabled' => '( !\''.$this->Permissions->check( 'Personnes', 'index' ).'\' )',
				),
				'/Personnes/index/#Foyer2.id#' => array(
					'disabled' => '( !\''.$this->Permissions->check( 'Personnes', 'index' ).'\' )',
				),
				'/Gestionsdoublons/fusion/#Foyer.id#/#Foyer2.id#' => array(
					'disabled' => '( \'#Dossier.locked#\' || \'#Dossier2.locked#\' || !\''.$this->Permissions->check( 'Gestionsdoublons', 'fusion' ).'\' )',
				)
			),
			array(
				'options' => $options
			)
		);

		echo str_replace(
			'<thead>',
			'<thead><tr><th colspan="8">Dossier</th><th colspan="8">Dossier temporaire</th><th colspan="3"></th></tr>',
			$index
		);
	}
?>