<?php
	$departement = Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	$paramDate = array(
		'domain' => null,
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 4
	);
	$notEmptyRule['notEmpty'] = array(
		'rule' => 'notEmpty',
		'message' => 'Champ obligatoire'
	);
	$dateRule['date'] = array(
		'rule' => array('date'),
		'message' => null,
		'required' => null,
		'allowEmpty' => true,
		'on' => null
	);
	$validation = array();
	echo $this->FormValidator->generateJavascript($validation, false);

	$this->start( 'custom_search_filters' );

	echo '<fieldset><legend>' . __m( ucfirst($controller) . $action ) . '</legend>';
	
	if ($action === 'search_radies') {
		echo $this->Form->input( 'Search.Historiqueetatpe.identifiantpe', array( 'label' => __m('Search.Historiqueetatpe.identifiantpe') ) );
	}	
	
	echo $this->Form->input( 'Search.Orientstruct.date_valid', array( 'label' => __m('Search.Orientstruct.date_valid'), 'type' => 'date', 'dateFormat' => 'MY', 'minYear' => 2009, 'maxYear' => date( 'Y' ) + 1, 'empty' => true ) )
		. '</fieldset>'
	;
	
	$this->end();

	$explAction = explode('_', $action);
	$exportcsvActionName = isset($explAction[1]) ? 'exportcsv_'.$explAction[1] : 'exportcsv';
	
	echo $this->element(
		'ConfigurableQuery/search',
		array(
			'customSearch' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => $exportcsvActionName ),
			'modelName' => 'Personne'
		)
	);
?>