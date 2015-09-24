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
		'Historiquepositioncui66' => array('created' => $dateRule),
		'Cui66' => array(
			'dateeligibilite' => $dateRule,
			'datereception' => $dateRule,
			'dateenvoi' => $dateRule,
		),
		'Emailcui' => array(
			'insertiondate' => $dateRule,
			'created' => $dateRule,
			'datecomplet' => $dateRule,
		),
		'Decisioncui66' => array('datedecision' => $dateRule),
		'Cui' => array(
			'dateembauche' => $dateRule,
			'findecontrat' => $dateRule,
			'effetpriseencharge' => $dateRule,
			'finpriseencharge' => $dateRule,
			'decisionpriseencharge' => $dateRule,
			'faitle' => $dateRule,
		),
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
	
	// Spécifique CG 66
	if ( $departement == 66 ){
		foreach( $options['Cui66']['etatdossiercui66'] as $key => $value ){
			$options['Cui66']['etatdossiercui66'][$key] = sprintf( $value, '(Date)' );
		}
		
		echo '<fieldset><legend id="Cui66Positions">' . __m( 'Cui66.positions' ) . '</legend>'
			. multipleCheckbox( $this, 'Search.Cui66.etatdossiercui66', $options, 'divideInto2Collumn' )
			. $this->SearchForm->dateRange( 'Search.Historiquepositioncui66.created', $paramDate )	
			. '</fieldset>'
		;
		
		echo '<fieldset><legend id="Cui66Choixformulaire">' . __m( 'Cui66.choixformulaire' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Cui66.typeformulaire' => array( 'empty' => true, 'options' => $options['Cui66']['typeformulaire'], 'label' => __m( 'Cui66.typeformulaire' ) )
				)
			) . '</fieldset>'
		;
		
		echo '<fieldset id="CuiSecteur"><legend>' . __m( 'Cui.secteur' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Cui.secteurmarchand' => array( 'empty' => true, 'label' => __m( 'Cui.secteurmarchand' ) ),
					'Search.Cui66.typecontrat' => array( 'empty' => true, 'label' => __m( 'Cui66.typecontrat' ), 'options' => $options['Cui66']['typecontrat_actif'] ),	
				),
				array( 'options' => array( 'Search' => $options ) )
			) . '</fieldset>'
		;
		
		echo '<fieldset id="Cui66Dossier"><legend>' . __m( 'Cui66.dossier' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Cui66.dossiereligible' => array( 'empty' => true, 'label' => __m( 'Cui66.dossiereligible' ) ),
					'Search.Cui66.dossierrecu' => array( 'empty' => true, 'label' => __m( 'Cui66.dossierrecu' ) ),
					'Search.Cui66.dossiercomplet' => array( 'empty' => true, 'label' => __m( 'Cui66.dossiercomplet' ) ),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. $this->SearchForm->dateRange( 'Search.Cui66.dateeligibilite', $paramDate )
			. $this->SearchForm->dateRange( 'Search.Cui66.datereception', $paramDate )
			. $this->SearchForm->dateRange( 'Search.Cui66.datecomplet', $paramDate )
			. '</fieldset>'
		;
		
		echo '<fieldset id="Cui66Email"><legend>' . __m( 'Cui66.email' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Emailcui.textmailcui66_id' => array( 'empty' => true, 'label' => __m( 'Emailcui.textmailcui66_id' ) ),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. $this->SearchForm->dateRange( 'Search.Emailcui.insertiondate', $paramDate )
			. $this->SearchForm->dateRange( 'Search.Emailcui.created', $paramDate )
			. $this->SearchForm->dateRange( 'Search.Emailcui.dateenvoi', $paramDate )
			. '</fieldset>'
		;
		
		echo '<fieldset id="Cui66Decision"><legend>' . __m( 'Cui66.decision' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Decisioncui66.decision' => array( 'empty' => true, 'label' => __m( 'Decisioncui66.decision' ) ),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. $this->SearchForm->dateRange( 'Search.Decisioncui66.datedecision', $paramDate )
			. '</fieldset>'
		;
	}
	
	echo '<fieldset id="CuiSituationsalarie"><legend>' . __m( 'Cui.situationsalarie' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Cui.niveauformation' => array( 'empty' => true, 'type' => 'select', 'label' => __m( 'Cui.niveauformation' ) ),
				'Search.Cui.inscritpoleemploi' => array( 'empty' => true, 'label' => __m( 'Cui.inscritpoleemploi' ) ),
				'Search.Cui.sansemploi' => array( 'empty' => true, 'label' => __m( 'Cui.sansemploi' ) ),
				'Search.Cui.beneficiairede' => array( 'empty' => true, 'label' => __m( 'Cui.beneficiairede' ) ),
				'Search.Cui.majorationrsa' => array( 'empty' => true, 'label' => __m( 'Cui.majorationrsa' ) ),
				'Search.Cui.rsadepuis' => array( 'empty' => true, 'label' => __m( 'Cui.rsadepuis' ) ),
				'Search.Cui.travailleurhandicape' => array( 'empty' => true, 'label' => __m( 'Cui.travailleurhandicape' ) ),
			),
			array( 'options' => array( 'Search' => $options ) )
		) . '</fieldset>'
	;

	echo '<fieldset id="CuiContrattravail"><legend>' . __m( 'Cui.contrattravail' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Cui.typecontrat' => array( 'empty' => true, 'label' => __m( 'Cui.typecontrat' ) ),
				'Search.Cui.partenaire_id' => array( 'empty' => true, 'label' => __d( 'cuis66', 'Cui.partenaire_id' ) ),
				'Search.Adressecui.commune' => array( 'empty' => true, 'label' => __d( 'cuis66', 'Adressecui.commune' ) ),
				'Search.Adressecui.canton' => array( 'empty' => true, 'label' => __d( 'cuis66', 'Adressecui.canton' ) ),
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		. $this->SearchForm->dateRange( 'Search.Cui.dateembauche', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Cui.findecontrat', $paramDate )
		. $this->Romev3->fieldset( 'Entreeromev3', array( 'options' => $options, 'prefix' => 'Search' ) )
		. '</fieldset>'
	;
	
	echo '<fieldset id="CuiPrise_en_charge"><legend>' . __m( 'Cui.prise_en_charge' ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Cui.effetpriseencharge', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Cui.finpriseencharge', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Cui.decisionpriseencharge', $paramDate )
		. '</fieldset>'
	;
	
	echo '<fieldset id="CuiPrise_en_charge"><legend>' . __m( 'Cui.date' ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Cui.faitle', $paramDate )
		. '</fieldset>'
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
		
		echo $this->element( 'search_footer' );
	}