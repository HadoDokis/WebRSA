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
	
	echo $this->Default3->titleForLayout( array(), array( 'domain' => $domain ) );
	
	// @param 1 Validation javascript, verification seulement sur date, 
	// @param 2 allowEmpty
	// @param 3 Verifications additionnelles
	// @param 4 ne regarde pas dans $this->request->data mais dans $this->request->data['Search']
	echo $this->FormValidator->checkOnly( 'date', true, null, 'Search' )->generateJavascript();

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
	}
	
	if( isset( $results ) ){
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