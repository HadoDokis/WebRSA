<?php
	$departement = Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	$paramDate = array(
		'domain' => $domain,
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 4
	);
	$paramAllocataire = array(
		'options' => $options,
		'prefix' => 'Search',
	);
	$dateRule = array(
		'date' => array(
			'rule' => array('date'),
			'message' => null,
			'required' => null,
			'allowEmpty' => true,
			'on' => null
		)
	);

	echo $this->Default3->titleForLayout();

	$dates = array(
		'Dossier' => array('dtdemrsa' => $dateRule),
		'Personne' => array('dtnai' => $dateRule),
	);
	echo $this->FormValidator->generateJavascript($dates, false);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->actions(
		array(
			'/' . $controller . '/' . $action . '/#toggleform' => array(
				'onclick' => '$(\'' . $formId . '\').toggle(); return false;',
				'class' => $action . 'Form',
				'domain' => $domain
			),
		)
	);

	// 1. Moteur de recherche
	echo $this->Xform->create( null,
		array(
			'id' => $formId,
			'class' => ( ( isset( $results ) ) ? 'folded' : 'unfolded' ),
			'url' => Router::url( array( 'controller' => $controller, 'action' => $action ), true )
		)
	);

	echo $this->Allocataires->blocDossier($paramAllocataire);

	echo $this->Allocataires->blocAdresse($paramAllocataire);

	echo $this->Allocataires->blocAllocataire($paramAllocataire);

	echo '<fieldset><legend>' . __m( 'Infofinanciere.search' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Infofinanciere.natpfcre' => array( 'empty' => true ),
				'Search.Dossier.typeparte' => array( 'empty' => true ),
				'Search.Infofinanciere.compare' => array( 'empty' => true ),
				'Search.Infofinanciere.mtmoucompta',
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;

	echo $this->Allocataires->blocReferentparcours($paramAllocataire);

	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );

	echo $this->Search->observeDisableFormOnSubmit( $formId );

	// 2. Résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Default3->configuredIndex(
			$results,
			array(
				'format' => $this->element( 'pagination_format', array( 'modelName' => 'Dossier' ) ),
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'modelName' => 'Dossier' ) );
	}
?>