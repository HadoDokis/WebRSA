<?php $this->start( 'custom_search_filters' );?>
<?php
	$departement = (int)Configure::read( 'Cg.departement' );
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

	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', 'Recherche par parcours de l\'allocataire' )
		.$this->Default3->subform(
			array(
				'Search.Historiqueetatpe.identifiantpe' => array( /*'maxlength' => 11*/ ),
				'Search.Personne.has_contratinsertion' => array( 'empty' => true ),
				'Search.Personne.has_personne_referent' => array( 'empty' => true ),
				'Search.Personne.is_inscritpe' => array( 'empty' => true )
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		.(
			( $departement !== 58 )
			? ''
			: $this->Default3->subform(
				array(
					'Search.Activite.act' => array( 'empty' => true )
				),
				array( 'options' => array( 'Search' => $options ) )
			)
		)
	);

	echo '<fieldset><legend>' . __m( 'Orientstruct.search' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Orientstruct.derniere' => array( 'type' => 'checkbox' )
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		. $this->SearchForm->dateRange( 'Search.Orientstruct.date_valid', $paramDate )
	;

	if ($departement === 66) {
		echo '<fieldset><legend>' . __m( 'Orientstruct.orientepar' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Orientstruct.structureorientante_id' => array('empty' => true, 'required' => false),
					'Search.Orientstruct.referentorientant_id' => array('empty' => true, 'required' => false),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. '</fieldset>'
		;
	}

	if ($departement === 93) {
		echo $this->Default3->subform(
			array(
				'Search.Orientstruct.origine' => array('empty' => true),
			),
			array( 'options' => array( 'Search' => $options ) )
		);
	}

	echo $this->Default3->subform(
			array(
				'Search.Orientstruct.typeorient_id' => array('empty' => true, 'required' => false),
				'Search.Orientstruct.structurereferente_id' => array('empty' => true, 'required' => false),
				'Search.Orientstruct.statut_orient' => array('empty' => true, 'required' => false)
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		. '</fieldset>'
	;
?>
<?php $this->end();?>

<?php
	echo $this->element(
		'ConfigurableQuery/search',
		array(
			'customSearch' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => 'exportcsv' )
		)
	);
?>
<?php
	if( $departement === 66 ) {
		echo $this->Observer->dependantSelect(
			array(
				'Search.Orientstruct.structureorientante_id' => 'Search.Orientstruct.referentorientant_id'
			)
		);
	}

	echo $this->Observer->dependantSelect(
		array(
			'Search.Orientstruct.typeorient_id' => 'Search.Orientstruct.structurereferente_id'
		)
	);
?>