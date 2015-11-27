<?php
	$controller = $this->params->controller;
	$availableDomains = MultiDomainsTranslator::urlDomains();
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	$paramDate = array( 
		'domain' => null, 
		'minYear_from' => '2009', 
		'maxYear_from' => date( 'Y' ) + 1, 
		'minYear_to' => '2009', 
		'maxYear_to' => date( 'Y' ) + 4
	);
	
	$this->start( 'custom_search_filters' );
	
	echo $this->Xform->multipleCheckbox( 'Search.Tag.valeurtag_id', $options['filter'] );
	
	if( Configure::read( 'CG.cantons' ) ) {
		echo $this->Xform->multipleCheckbox( 'Search.Zonegeographique.id', $options, 'divideInto2Collumn' );
	}
	
	echo $this->Xform->multipleCheckbox( 'Search.Prestation.rolepers', $options, 'divideInto2Collumn' );
	echo $this->Xform->multipleCheckbox( 'Search.Foyer.composition', $options, 'divideInto2Collumn' );
	
	echo '<fieldset><legend>' . __m( 'Tag.cohorte_fieldset' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Foyer.nb_enfants',
				'Search.Adresse.heberge' => array( 'empty' => true ),
				'Search.Requestmanager.name' => array( 'empty' => true ),
			),
			array(
				'options' => array( 'Search' => $options )
			)
		)
		. '</fieldset>'
	;
	
	$this->end();

	$configuredCohorteParams['class'] = 'test';
	echo $this->element(
		'ConfigurableQuery/cohorte',
		array(
			'custom' => $this->fetch( 'custom_search_filters' ),
		)
	);
	
	$results = isset($results) ? $results : array();
	
	foreach ($results as $i => $result) {
	?>
		<script type="text/javascript">
			Event.observe( $('Cohorte<?php echo $i?>TagCalcullimite'), 'change', function() {
				setDateCloture('Cohorte<?php echo $i?>TagCalcullimite', 'Cohorte.<?php echo $i?>.Tag.limite');
			});

			Event.observe( $('Cohorte<?php echo $i?>Traitementpcg66Dureeecheance'), 'change', function() {
				setDateCloture('Cohorte<?php echo $i?>Traitementpcg66Dureeecheance', 'Cohorte.<?php echo $i?>.Traitementpcg66.dateecheance');
			});
		</script>
	<?php }