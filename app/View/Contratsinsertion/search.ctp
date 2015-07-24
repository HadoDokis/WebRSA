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
		'Contratinsertion' => array(
			'created' => $dateRule,
			'datevalidation_ci' => $dateRule,
			'dd_ci' => $dateRule,
			'df_ci' => $dateRule,
			'periode_validite' => $dateRule,
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
	
	echo '<fieldset><legend>' . __m( 'Contratinsertion.search' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Contratinsertion.dernier' => array( 'type' => 'checkbox' ),
				'Search.Contratinsertion.forme_ci' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __m('Search.Contratinsertion.forme_ci') )
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		) 
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.created', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Contratinsertion.structurereferente_id' => array( 'empty' => true ),
				'Search.Contratinsertion.referent_id' => array( 'empty' => true ),
				'Search.Contratinsertion.decision_ci' => array( 'empty' => true ),
				'Search.Contratinsertion.positioncer' => array( 'empty' => true ),
				'Search.Contratinsertion.duree_engag' => array( 'empty' => true ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.datevalidation_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.dd_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.df_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.periode_validite', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Contratinsertion.arriveaecheance' => array( 'type' => 'checkbox' ),
				'Search.Contratinsertion.echeanceproche' => array( 'type' => 'checkbox' ),
				'Search.Contratinsertion.istacitereconduction' => array( 'type' => 'checkbox' ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;
	
	
	echo '<fieldset><legend>' . __m( 'Contratinsertion.orientation' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Orientstruct.typeorient_id' => array( 'type' => 'select', 'multiple' => 'checkbox' ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
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
		
		echo '<ul class="actionMenu"><li>'
			. $this->Xhtml->exportLink(
				'Télécharger le tableau',
				array( 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
				( $this->Permissions->check( $this->request->params['controller'], 'exportcsv' ) && count( $results ) > 0 )
			)
			. '</li></ul>'
		;
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'SearchContratinsertionReferentId', 'SearchContratinsertionStructurereferenteId' );
	});
</script>