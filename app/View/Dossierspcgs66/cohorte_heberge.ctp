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
			'tableClass' => 'results_table full_size horizontal_view'
		)
	);
	
	/**
	 * INCRUSTATION
	 */
	?>
	<script type="text/javascript">
		
		/**
		 * Permet d'adapter un formulaire reçu par ajax à la cohorte, CAD un id préfixé par Cohorte0 et un name par data[Cohorte][0] (lorsque i = 0)
		 */
		function rebuildIds( divId ) {
			var i = divId.substr(7, divId.indexOf('Typecourrierpcg66') -7); // ex: Cohorte0Typecourrierpcg66Modeletypecourrierpcg66Id
			$$('#'+divId+' *').each(function(element) {
				if (element.id !== undefined && element.id !== '') {
					element.id = 'Cohorte'+i+element.id;
				}
				
				if (element.readAttribute('for') !== null) {
					element.writeAttribute('for', 'Cohorte'+i+element.readAttribute('for'));
				}
				
				if (element.readAttribute('name') !== null) {
					element.writeAttribute('name', 'data[Cohorte]['+i+']'+element.readAttribute('name').substr(4));
				}
			});
		}
		
		/**
		 * Reconstruit les dependents select avec les nouveaux name
		 * @param {integer} i
		 */
		function dependentSelectRemake( i ) {
			$$('#Cohorte'+i+'Modeletraitementpcg66Modeletypecourrierpcg66Id fieldset').each(function(element) {
				var baseId = 'Cohorte'+i+'detailsmodelelie';
				if ( element.id.indexOf(baseId) === 0 ) {
					var id = element.id.substr(baseId.length);
					
					observeDisableFieldsetOnRadioValue(
						'Dossierspcgs66CohorteHebergeCohorte',
						'data[Cohorte]['+i+'][Modeletraitementpcg66][modeletypecourrierpcg66_id]',
						element.id,
						id,
						false,
						true
					);
				}
			});
			
		}
	</script>
	<?php
	$results = isset($results) ? $results : array();
	
	foreach ($results as $i => $result) {
	?>
    <script type="text/javascript">
		var afficheCoupleDiv<?php echo $i;?>,
			div<?php echo $i;?>,
			i = <?php echo $i?>
		;
		
		Event.observe( $('Cohorte'+i+'TagCalcullimite'), 'change', function() {
			setDateCloture('Cohorte'+i+'TagCalcullimite', 'Cohorte.'+i+'.Tag.limite');
		});
		
		Event.observe( $('Cohorte'+i+'Traitementpcg66Dureeecheance'), 'change', function() {
			setDateCloture('Cohorte'+i+'Traitementpcg66Dureeecheance', 'Cohorte.'+i+'.Traitementpcg66.dateecheance');
		});
		
		afficheCoupleDiv<?php echo $i;?> = $('Cohorte<?php echo $i;?>Traitementpcg66AfficheCouple').up();
		div<?php echo $i;?> = new Element('div', {
			'id': 'Cohorte<?php echo $i;?>Typecourrierpcg66Modeletypecourrierpcg66Id'
		});
		afficheCoupleDiv<?php echo $i;?>.insert({after:div<?php echo $i;?>});
		
		function showAjaxValidationErrors() {/*
			<?php if( isset( $this->validationErrors['Modeletraitementpcg66'] ) ) :?>
				<?php foreach( $this->validationErrors['Modeletraitementpcg66'] as $field => $errors ):?>
					var div = $( '<?php echo Inflector::camelize( "Modeletraitementpcg66_{$field}" );?>' );
					$( div ).addClassName( 'error' );
					var errorMessage = new Element( 'div', { 'class': 'error-message' } ).update( '<?php echo $errors[0];?>' );
					$( div ).insert( { 'bottom' : errorMessage } );
				<?php endforeach;?>
			<?php endif;?>
		*/}

		document.observe("dom:loaded", function() {
			<?php
				// FIXME: une fonction générique
				$dataModeletraitementpcg66 = array();
				foreach( array( 'Modeletraitementpcg66', 'Piecemodeletypecourrierpcg66' ) as $M ) {
					if( isset( $this->request->data[$M] ) && !empty( $this->request->data[$M] ) ) {
						foreach( $this->request->data[$M] as $field => $value ) {
							if( !is_array( $value ) ) {
								$dataModeletraitementpcg66["data[{$M}][{$field}]"] = js_escape( $value );
							}
							else {
								foreach( $value as $k => $v ) {
									$dataModeletraitementpcg66["data[{$M}][{$field}][{$k}]"] = js_escape( $v );
								}
							}
						}
					}
				}
				
				$ajaxParams = array(
					'update' => 'Cohorte'.$i.'Typecourrierpcg66Modeletypecourrierpcg66Id',
					'url' => array( 'controller' => 'traitementspcgs66', 'action' => 'ajaxpiece_cohorte', $i ),
					'with' => 'Form.serialize( $(\'Dossierspcgs66CohorteHebergeCohorte\') )',
					'complete' => 'showAjaxValidationErrors(); rebuildIds("Cohorte'.$i.'Typecourrierpcg66Modeletypecourrierpcg66Id"); dependentSelectRemake('.$i.');',
					'evalScripts' => true
				);

				echo $this->Ajax->remoteFunction(
					$ajaxParams
				);
			?>
			
			// Rempli Traitementpcg66.personnepcg66_situationpdo_id en fonction de Situationpdo.Situationpdo
			$$('input[type="checkbox"][name="data[Cohorte]['+i+'][Situationpdo][Situationpdo][]"]').each(function(element){
				element.observe('change', function(){
					var select = $('Cohorte'+i+'Traitementpcg66Personnepcg66SituationpdoId');
					select.innerHTML = '';
					
					$$('input[type="checkbox"][name="data[Cohorte]['+i+'][Situationpdo][Situationpdo][]"]').each(function(element){
						if (element.getValue()) {
							var option = new Element('option', {
								'value': element.getValue()
							});
							option.insert(element.up('div').select('label').first().innerHTML);
							select.insert(option);
						}
					});
				});
			});
			
		} );
    </script>
		<fieldset id="filecontainer-courrier-<?php echo $i; ?>" class="noborder invisible">
			<?php
				echo $this->Ajax->observeField(
					'Cohorte'.$i.'Traitementpcg66Typecourrierpcg66Id',
					$ajaxParams
				);
			?>
		</fieldset>
	<?php } ?>