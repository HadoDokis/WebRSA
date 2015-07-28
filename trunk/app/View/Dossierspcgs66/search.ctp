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
	
	echo $this->Allocataires->blocReferentparcours($paramAllocataire);
	
	echo '<fieldset><legend>' . __m( 'Dossierpcg66.search' ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Dossierpcg66.datereceptionpdo', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Dossierpcg66.originepdo_id' => array( 'empty' => true ),
				'Search.Dossierpcg66.typepdo_id' => array( 'empty' => true ),
				'Search.Dossierpcg66.orgpayeur' => array( 'empty' => true ),
				'Search.Dossierpcg66.poledossierpcg66_id' => array( 'type' => 'select', 'multiple' => 'checkbox' ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		) 
		. $this->Xform->input('Search.Dossierpcg66.user_id', array('label' => __m('Search.Dossierpcg66.user_id'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Dossierpcg66']['user_id'], 'class' => 'divideInto3Collumn'))
		. $this->SearchForm->dateRange( 'Search.Dossierpcg66.dateaffectation', $paramDate )
		. $this->Xform->input('Search.Dossierpcg66.etatdossierpcg', array('label' => __m('Search.Dossierpcg66.etatdossierpcg'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Dossierpcg66']['etatdossierpcg'], 'class' => 'divideInto2Collumn'))
		. $this->Xform->input('Search.Decisiondossierpcg66.org_id', array('label' => __m('Search.Decisiondossierpcg66.org_id'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Decisiondossierpcg66']['org_id'], 'class' => 'divideInto2Collumn'))
		. $this->Xform->input('Search.Traitementpcg66.situationpdo_id', array('label' => __m('Search.Traitementpcg66.situationpdo_id'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Traitementpcg66']['situationpdo_id'], 'class' => 'divideInto2Collumn'))
		. $this->Xform->input('Search.Traitementpcg66.statutpdo_id', array('label' => __m('Search.Traitementpcg66.statutpdo_id'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Traitementpcg66']['statutpdo_id'], 'class' => 'divideInto2Collumn'))
		. $this->Default3->subform(
			array(
				'Search.Decisiondossierpcg66.useravistechnique_id' => array( 'empty' => true, 'options' => $options['Dossierpcg66']['user_id'] ),
				'Search.Decisiondossierpcg66.userproposition_id' => array( 'empty' => true, 'options' => $options['Dossierpcg66']['user_id'] ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. $this->Xform->input('Search.Decisiondossierpcg66.decisionpdo_id', array('label' => __m('Search.Decisiondossierpcg66.decisionpdo_id'), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Decisiondossierpcg66']['decisionpdo_id'], 'class' => 'divideInto2Collumn'))
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
		
		echo '<ul class="actionMenu"><li>'
			. $this->Xhtml->exportLink(
				'Télécharger le tableau',
				array( 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
				( $this->Permissions->check( $this->request->params['controller'], 'exportcsv' ) && count( $results ) > 0 )
			)
			. '</li></ul>'
		;
	}