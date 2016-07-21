<?php
	$departement = Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = WebrsaTranslator::domains();
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
	$validationCohorte = array(
		'Aideapre66' => array(
			'montantaccorde' => array('numeric' => array('rule' => array('numeric'), 'allowEmpty' => false)),
			'datemontantaccorde' => $dateRule
		),
	);
	echo $this->FormValidator->generateJavascript($validationCohorte, false);

	$this->start( 'custom_search_filters' );

	echo '<fieldset><legend>' . __m( 'Apres66.'.$action ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Aideapre66.themeapre66_id' => array( 'empty' => true ),
				'Search.Aideapre66.typeaideapre66_id' => array( 'empty' => true ),
				'Search.Apre66.numeroapre',
				'Search.Apre66.referent_id' => array( 'empty' => true ),
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;

	$this->end();

	$explAction = explode('_', $action);
	$exportcsvActionName = isset($explAction[1]) ? 'exportcsv_'.$explAction[1] : 'exportcsv';
	
	echo $this->element(
		'ConfigurableQuery/cohorte',
		array(
			'customSearch' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => $exportcsvActionName ),
			'modelName' => 'Apre66'
		)
	);

	$results = isset($results) ? $results : array();
	
	foreach ($results as $i => $result) {
	?>
		<script type="text/javascript">
			observeDisableElementsOnValues(
				[
					'Cohorte<?php echo $i;?>Aideapre66Decisionapre',
					'Cohorte<?php echo $i;?>Aideapre66DatemontantaccordeDay',
					'Cohorte<?php echo $i;?>Aideapre66DatemontantaccordeMonth',
					'Cohorte<?php echo $i;?>Aideapre66DatemontantaccordeYear'
				],
				[
					{element: 'Cohorte<?php echo $i;?>Apre66Selection', value: '1', operateur: '!='}
				]
			);
	
			observeDisableElementsOnValues(
				'Cohorte<?php echo $i;?>Aideapre66Montantaccorde', 
				[
					{element: 'Cohorte<?php echo $i;?>Apre66Selection', value: '1', operateur: '!='},
					{element: 'Cohorte<?php echo $i;?>Aideapre66Decisionapre', value: 'REF', operateur: '=='}
				],
				false, // Hide
				true   // Une condition rempli suffit à désactiver l'element
			);
	
			observeDisableElementsOnValues(
				'Cohorte<?php echo $i;?>Aideapre66Motifrejetequipe', 
				[
					{element: 'Cohorte<?php echo $i;?>Apre66Selection', value: '1', operateur: '!='},
					{element: 'Cohorte<?php echo $i;?>Aideapre66Decisionapre', value: 'ACC', operateur: '=='}
				],
				false, // Hide
				true   // Une condition rempli suffit à désactiver l'element
			);
		</script>
	<?php
	}
	?>
		<script type="text/javascript">
			 dependantSelect(
                'SearchAideapre66Typeaideapre66Id',
                'SearchAideapre66Themeapre66Id'
            );
		</script>