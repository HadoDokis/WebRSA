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
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js', 'prototype.maskedinput.js' ), array( 'inline' => false ) );
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
			array_merge(
				array(
					'Search.Contratinsertion.dernier' => array( 'type' => 'checkbox' ),
				),
				(
					( !in_array( $departement, array( 58, 976 ), true ) )
					? array(
						'Search.Contratinsertion.forme_ci' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __m('Search.Contratinsertion.forme_ci') )
					)
					: array()
				)
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.created', $paramDate )
		. $this->Default3->subform(
			array_merge(
				array(
					'Search.Contratinsertion.structurereferente_id' => array( 'empty' => true ),
					'Search.Contratinsertion.referent_id' => array( 'empty' => true ),
				),
				(
					$departement === 93
					? array(
						'Search.Cer93.positioncer' => array( 'empty' => true, 'required' => false ),
					)
					: array(
						'Search.Contratinsertion.decision_ci' => array( 'empty' => true )
					)
				),
				(
					$departement === 66
					? array(
						'Search.Contratinsertion.positioncer' => array( 'empty' => true ),
					)
					: array()
				),
				array(
					'Search.Contratinsertion.duree_engag' => array( 'empty' => true, 'type' => ( $departement === 58 ? 'text' : 'select' ) ),
				)
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		);

		if( $departement === 93 ) {
			// 1. Partie "Expériences professionnelles significatives"
			echo $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', __m( 'Expprocer93Expprocer93' ) )
				.$this->Romev3->fieldset( 'Expprocer93', array( 'options' => array( 'Expprocer93' => $options['Catalogueromev3'] ), 'multi_domain_translator' => true, 'prefix' => 'Search' ) )
				.$this->Html->tag(
					'fieldset',
					$this->Html->tag( 'legend', __d( 'cg93_contratsinsertion_search', 'Expprocer93Insee' ) )
					.$this->Xform->input( 'Search.Expprocer93.secteuracti_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.secteuracti_id' ), 'empty' => true, 'domain' => 'cg93_contratsinsertion_search' ) )
					.$this->Xform->input( 'Search.Expprocer93.metierexerce_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.metierexerce_id' ), 'empty' => true, 'domain' => 'cg93_contratsinsertion_search' ) )
				)
			);

			// 2. Partie "Emploi trouvé"
			echo $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', __m( 'Emptrouvromev3Emptrouvromev3' ) )
				.$this->Romev3->fieldset( 'Emptrouvromev3', array( 'options' => array( 'Emptrouvromev3' => $options['Catalogueromev3'] ), 'domain' => 'cg93_contratsinsertion_search', 'prefix' => 'Search', 'multi_domain_translator' => true  ) )
				.$this->Html->tag(
					'fieldset',
					$this->Html->tag( 'legend', __d( 'cg93_contratsinsertion_search', 'Emptrouvromev3Insee' ) )
					.$this->Xform->input( 'Search.Cer93.secteuracti_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.secteuracti_id' ), 'empty' => true, 'domain' => 'cg93_contratsinsertion_search' ) )
					.$this->Xform->input( 'Search.Cer93.metierexerce_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.metierexerce_id' ), 'empty' => true, 'domain' => 'cg93_contratsinsertion_search' ) )
				)
			);

			echo $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'Filtrer par ce sur quoi le contrat porte' )
				.$this->Form->input( 'Search.Cer93Sujetcer93.sujetcer93_id', array( 'label' => 'Sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.sujetcer93_id' ), 'empty' => true ) )
				.$this->Form->input( 'Search.Cer93Sujetcer93.soussujetcer93_id', array( 'label' => 'Sous sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.soussujetcer93_id' ), 'empty' => true ) )
				.$this->Form->input( 'Search.Cer93Sujetcer93.valeurparsoussujetcer93_id', array( 'label' => 'Valeur par sous sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.valeurparsoussujetcer93_id' ), 'empty' => true ) )
				.$this->Romev3->fieldset( 'Sujetromev3', array( 'options' => array( 'Sujetromev3' => $options['Catalogueromev3'] ), 'prefix' => 'Search' ) )
			);

			// Activation / désactivation de la partie "Votre contrat porte sur l'emploi (ROME v.3)" en fonciton des réponses à "Votre contrat porte sur"
			$activationPath = Configure::read( 'Cer93.Sujetcer93.Romev3.path' );
			$activationValues = (array)Configure::read( 'Cer93.Sujetcer93.Romev3.values' );

			$activationSujetcer93 = ( 'Sujetcer93.Sujetcer93.{n}.sujetcer93_id' === $activationPath );
			$activationSoussujetcer93 = ( 'Sujetcer93.Sujetcer93.{n}.soussujetcer93_id' === $activationPath );
			$activationIds = array();

			if( $activationSujetcer93 ) {
				$master = 'Search.Cer93Sujetcer93.sujetcer93_id';
				$activationIds = $activationValues;
			}
			else if( $activationSoussujetcer93 ) {
				$master = 'Search.Cer93Sujetcer93.soussujetcer93_id';
				foreach( array_keys( $options['Cer93Sujetcer93']['soussujetcer93_id'] ) as $soussujetcer93_id ) {
					if( in_array( suffix( $soussujetcer93_id ), $activationValues ) ) {
						$activationIds[] = $soussujetcer93_id;
					}
				}
			}

			if( $activationSujetcer93 || $activationSoussujetcer93 ) {
				echo $this->Observer->disableFieldsetOnValue(
					$master,
					'SearchSujetromev3FieldsetId',
					$activationIds,
					false,
					true
				);
			}
		}

		echo $this->SearchForm->dateRange( 'Search.Contratinsertion.datevalidation_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.dd_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.df_ci', $paramDate )
		. $this->SearchForm->dateRange( 'Search.Contratinsertion.periode_validite', $paramDate )
		. $this->Default3->subform(
			array_merge(
				array(
					'Search.Contratinsertion.arriveaecheance' => array( 'type' => 'checkbox' ),
					'Search.Contratinsertion.echeanceproche' => array( 'type' => 'checkbox' ),
				),
				(
					$departement === 66
					? array(
						'Search.Contratinsertion.istacitereconduction' => array( 'type' => 'checkbox' )
					)
					: array()
				)
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;

	echo '<fieldset><legend>' . __m( 'Contratinsertion.orientation' ) . '</legend>'
		. $this->Default3->subform(
			array_merge(
				(
					$departement === 58
					? array(
						'Search.Personne.etat_dossier_orientation' => array( 'type' => 'select', 'empty' => true ),
					)
					: array()
				),
				array(
					'Search.Orientstruct.typeorient_id' => array( 'type' => 'select', 'empty' => true ),
				),
				(
					$departement === 66
					? array(
						'Search.Orientstruct.not_typeorient_id' => array( 'type' => 'select', 'multiple' => 'checkbox' ),
					)
					: array()
				)
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;

	echo $this->Allocataires->blocReferentparcours($paramAllocataire);

	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );

	echo $this->Search->observeDisableFormOnSubmit( $formId );

	if( $departement === 93 ) {
		echo $this->Observer->dependantSelect(
			array(
				'Search.Cer93Sujetcer93.sujetcer93_id' => 'Search.Cer93Sujetcer93.soussujetcer93_id',
				'Search.Cer93Sujetcer93.soussujetcer93_id' => 'Search.Cer93Sujetcer93.valeurparsoussujetcer93_id',
			)
		);
	}

	// 2. Formulaire de traitement des résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Default3->configuredIndex(
			$results,
			array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ),
				'options' => $options
			)
		);

		echo $this->element( 'search_footer' );
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php if( $departement == 58 ): ?>
			new MaskedInput( '#SearchContratinsertionDureeEngag', '9?9' );
		<?php endif;?>
		dependantSelect( 'SearchContratinsertionReferentId', 'SearchContratinsertionStructurereferenteId' );
	});
</script>