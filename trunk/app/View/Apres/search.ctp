<?php
	$departement = (int)Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	$paramDate = array(
//		'domain' => $domain,
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
		'Apre' => array('datedemandeapre' => $dateRule)
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
				'class' => $action . 'Form searchForm',
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

	echo '<fieldset><legend>' . __m( 'Apre.search' ) . '</legend>'
		. (
			$departement === 93 && $this->request->action === 'search'
			? $this->Default3->subform(
				array(
					'Search.Apre.statutapre',
					'Search.Tiersprestataireapre.id' => array('empty' => true)
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			: ''
		)
		. $this->SearchForm->dateRange( 'Search.Apre.datedemandeapre', $paramDate + array( 'legend' => __m( 'Search.Apre.datedemandeapre' ) ) )
	;

	if ( $departement === 66 ) {
			echo $this->Default3->subform(
				array(
					'Search.Apre.structurereferente_id' => array('empty' => true),
					'Search.Apre.referent_id' => array('empty' => true),
					'Search.Apre.activitebeneficiaire' => array('empty' => true),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. $this->Default3->subform(
				array(
					'Search.Aideapre66.themeapre66_id' => array('empty' => true),
					'Search.Aideapre66.typeaideapre66_id' => array('empty' => true),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. $this->Default3->subform(
				array(
					'Search.Apre.etatdossierapre' => array('empty' => true),
					'Search.Apre.isdecision' => array('type' => 'radio', 'class' => 'uncheckable', 'legend' => __m('Search.Apre.isdecision')),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
		;
	}
	else if ( $departement === 93 ) { // FIXME: en fait, tout le monde (?), mais pas dans la même vue
		echo $this->Default3->subform(
			array_merge(
				(
					$this->request->action === 'search_eligibilite'
						? array(
							'Search.Apre.eligibiliteapre' => array( 'empty' => true ),
						)
						: array()
				),
				array(
					'Search.Apre.typedemandeapre' => array( 'empty' => true),
					'Search.Apre.activitebeneficiaire' => array('empty' => true),
					'Search.Apre.natureaide' => array('empty' => true),
				)
			),
			array( 'options' => array( 'Search' => $options ) )
		);
	}
	echo '</fieldset>';

	echo '<fieldset><legend>' . __m( 'Search.Relanceapre' ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Relanceapre.daterelance', $paramDate + array( 'legend' => __m( 'Search.Relanceapre.daterelance' ) ) )
		. $this->Default3->subform(
			array(
				'Search.Apre.etatdossierapre' => array('empty' => true)
			),
			array( 'options' => array( 'Search' => $options ) )
		)
	;
	echo '</fieldset>';


	echo $this->Allocataires->blocReferentparcours($paramAllocataire);

	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );

	echo $this->Search->observeDisableFormOnSubmit( $formId );

	// 2. Formulaire de traitement des résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		if( isset( $count_apres_statut ) ) {
			$msgid = 'Nombre total d\'APREs: %d, dont %d en attente de décision et %d en attente de traitement';
			$total = (int)Hash::get( $count_apres_statut, 'autre' ) + (int)Hash::get( $count_apres_statut, 'decision' ) + (int)Hash::get( $count_apres_statut, 'traitement' );
			echo $this->Html->tag( 'p', sprintf( __m( $msgid ), $total, (int)Hash::get( $count_apres_statut, 'decision' ), (int)Hash::get( $count_apres_statut, 'traitement' ) ) );
		}

		echo $this->Default3->configuredIndex(
			$results,
			array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ),
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'url' => array( 'action' => str_replace( 'search', 'exportcsv', $this->request->action ) ) ) );
	}

?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'SearchApreReferentId', 'SearchApreStructurereferenteId' );
		dependantSelect( 'SearchAideapre66Typeaideapre66Id', 'SearchAideapre66Themeapre66Id' );
	});
</script>