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
	
	function multipleCheckbox( $View, $path, $options, $class = '' ) {
		$name = model_field($path);
		return $View->Xform->input($path, array(
			'label' => __m($path), 
			'type' => 'select', 
			'multiple' => 'checkbox', 
			'options' => $options[$name[0]][$name[1]],
			'class' => $class
		));
	}
	
	echo $this->Default3->titleForLayout( array(), array( 'domain' => $domain ) );
	
	$dates = array(
		'Dossier' => array('dtdemrsa' => $dateRule),
		'Personne' => array('dtnai' => $dateRule),
		'Dossierpcg66' => array(
			'datereceptionpdo' => $dateRule,
			'dateaffectation' => $dateRule,
		),
		'Decisiondossierpcg66' => array(
			'datevalidation' => $dateRule,
			'datetransmissionop' => $dateRule,
		)
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
	
	echo '<fieldset><legend>' . __m( 'Dossierpcg66.search' ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Dossierpcg66.datereceptionpdo', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Dossierpcg66.originepdo_id' => array( 'empty' => true ),
				'Search.Dossierpcg66.typepdo_id' => array( 'empty' => true ),
				'Search.Dossierpcg66.orgpayeur' => array( 'empty' => true ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		) 
		. multipleCheckbox( $this, 'Search.Dossierpcg66.poledossierpcg66_id', $options )
		. multipleCheckbox( $this, 'Search.Dossierpcg66.user_id', $options, 'divideInto3Collumn' )
		. $this->SearchForm->dateRange( 'Search.Dossierpcg66.dateaffectation', $paramDate )
		. multipleCheckbox( $this, 'Search.Dossierpcg66.etatdossierpcg', $options, 'divideInto2Collumn' )
		. multipleCheckbox( $this, 'Search.Decisiondossierpcg66.org_id', $options, 'divideInto2Collumn' )
		. multipleCheckbox( $this, 'Search.Traitementpcg66.situationpdo_id', $options, 'divideInto2Collumn' )
		. multipleCheckbox( $this, 'Search.Traitementpcg66.statutpdo_id', $options, 'divideInto2Collumn' )
		. $this->Default3->subform(
			array(
				'Search.Decisiondossierpcg66.useravistechnique_id' => array( 'empty' => true, 'options' => $options['Dossierpcg66']['user_id'] ),
				'Search.Decisiondossierpcg66.userproposition_id' => array( 'empty' => true, 'options' => $options['Dossierpcg66']['user_id'] ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. multipleCheckbox( $this, 'Search.Decisiondossierpcg66.decisionpdo_id', $options, 'divideInto2Collumn' )
		. $this->SearchForm->dateRange( 'Search.Decisiondossierpcg66.datevalidation', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Decisiondossierpcg66.datetransmissionop', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Decisiondossierpcg66.nbproposition',
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		) 
		. '</fieldset>'
		. $this->Romev3->fieldset( 'Categorieromev3', array( 'options' => $options, 'prefix' => 'Search' ) )
	;
	
	echo $this->Allocataires->blocReferentparcours($paramAllocataire);
	
	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );
	
	echo $this->Search->observeDisableFormOnSubmit( $formId );

	// 2. Formulaire de traitement des résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Default3->configuredIndex(
			$results,
			array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ),
				'options' => $options
			)
		);
		
		echo $this->element( 'search_footer' );
	}