<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->DefaultForm->create( 'Ficheprescription93' );

	echo $this->Default3->subform(
		array(
			'Ficheprescription93.id' => array( 'type' => 'hidden' ),
			'Ficheprescription93.personne_id' => array( 'type' => 'hidden' ),
		)
	);

	// Cadre prescripteur / référent
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Prescripteur' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.structurereferente_id' => array( 'empty' => true ),
				'Ficheprescription93.referent_id' => array( 'empty' => true ),
			),
			array(
				'options' => $options,
			)
		)
		.$this->Html->tag( 'div', ' ', array( 'id' => 'CoordonneesPrescripteur' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.objet',
			),
			array(
				'options' => $options,
			)
		)
	);

	// Cadre bénéficiaire
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Beneficiaire' ) )
		.$this->Default3->subform(
			array(
				'Instantanedonneesfp93.benef_qual' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
					'options' =>(array) Hash::get( $options ,'Personne.qual' )
				),
				'Instantanedonneesfp93.benef_nom' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_prenom' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_dtnai' => array(
					'view' => true,
					'type' => 'date',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_adresse' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_codepos' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_locaadr' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				// TODO: adresse
				'Instantanedonneesfp93.benef_tel_fixe',
				'Instantanedonneesfp93.benef_tel_port',
				'Instantanedonneesfp93.benef_email',
				'Instantanedonneesfp93.benef_natpf' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
					'options' => $options['Instantanedonneesfp93']['benef_natpf'],
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_matricule' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_inscritpe' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_identifiantpe' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
				'Instantanedonneesfp93.benef_nivetu' => array( 'empty' => true ),
				'Instantanedonneesfp93.benef_dip_ce' => array( 'empty' => true ),
				'Instantanedonneesfp93.benef_positioncer' => array(
					'view' => true,
					'type' => 'text',
					'hidden' => true,
				),
			),
			array(
				'options' => $options
			)
		)
	);

	// Liens du catalogue
	$links = '';
	foreach( (array)Configure::read( 'Cataloguepdifp93.urls' ) as $text => $url ) {
		$links .= $this->Html->tag( 'li', $this->Html->link( $text, $url, array( 'class' => 'external' ) ) );
	}
	if( !empty( $links ) ) {
		$links = $this->Html->tag( 'ul', $links );
	}

	// Cadre prestataire / partenaire
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Prestataire' ) )
		.$links
		.$this->Default3->subform(
			array(
				'Ficheprescription93.rdvprestataire_date' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
				'Ficheprescription93.rdvprestataire_personne' => array( 'type' => 'text' ),
				'Ficheprescription93.numconvention' => array( 'type' => 'text' ),
				'Thematiquefp93.type' => array( 'empty' => true ),
				'Categoriefp93.thematiquefp93_id' => array( 'empty' => true ),
				'Filierefp93.categoriefp93_id' => array( 'empty' => true ),
				'Actionfp93.filierefp93_id' => array( 'empty' => true ),
				'Actionfp93.prestatairefp93_id' => array( 'empty' => true ),
				'Ficheprescription93.actionfp93_id' => array( 'empty' => true ),
				'Ficheprescription93.statut' => array( 'type' => 'hidden' ), // FIXME ?
				'Ficheprescription93.dd_action' => array( 'empty' => true, 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1 ),
				'Ficheprescription93.df_action' => array( 'empty' => true, 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1 ),
				// TODO: duree_action,
				'Documentbeneffp93.Documentbeneffp93' => array( 'multiple' => 'checkbox' ),
				'Ficheprescription93.documentbeneffp93_autre',
			),
			array(
				'options' => $options,
			)
		)
	);

	// Cadre "Engagement"
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Engagement' ) )
		.$this->Html->tag( 'p', __d( $this->request->params['controller'], 'Ficheprescription93.texte_engagement' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.date_signature' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
			),
			array(
				'options' => $options,
			)
		)
	);

	// Cadre "Modalités de transmission"
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Transmission' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.date_transmission' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
				'Modtransmfp93.Modtransmfp93' => array( 'multiple' => 'checkbox' ),
			),
			array(
				'options' => $options,
			)
		)
	);

	// Cadre "Résultat de l'effectivité de la prescription"
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Effectivite' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.date_retour' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
				'Ficheprescription93.retour_nom_partenaire' => array( 'type' => 'text' ),
				'Ficheprescription93.benef_retour_presente' => array( 'empty' => true ),
				'Ficheprescription93.date_signature_partenaire' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
			),
			array(
				'options' => $options,
			)
		)
	);

	// Cadre "Suivi de l'action"
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Suivi' ) )
		.$this->Default3->subform(
			array(
				'Ficheprescription93.personne_recue' => array( 'empty' => true ),
				'Ficheprescription93.motifnonreceptionfp93_id' => array( 'empty' => true ),
				'Ficheprescription93.personne_nonrecue_autre',

				'Ficheprescription93.personne_retenue' => array( 'empty' => true ),
				'Ficheprescription93.motifnonretenuefp93_id' => array( 'empty' => true ),
				'Ficheprescription93.personne_nonretenue_autre',

				'Ficheprescription93.personne_souhaite_integrer' => array( 'empty' => true ),
				'Ficheprescription93.motifnonsouhaitfp93_id' => array( 'empty' => true ),
				'Ficheprescription93.personne_nonsouhaite_autre',

				'Ficheprescription93.personne_a_integre' => array( 'empty' => true ),
				'Ficheprescription93.personne_date_integration' => array( 'dateFormat' => 'DMY' ),
				'Ficheprescription93.motifnonintegrationfp93_id' => array( 'empty' => true ),
				'Ficheprescription93.personne_nonintegre_autre',

				'Ficheprescription93.date_bilan_mi_parcours' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
				'Ficheprescription93.date_bilan_final' => array( 'empty' => true, 'dateFormat' => 'DMY', 'timeFormat' => 24, 'maxYear' => date( 'Y' ) + 1 ),
			),
			array(
				'options' => $options,
			)
		)
	);

	echo $this->Default3->DefaultForm->buttons( array( 'Validate', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
?>
<?php
	// Le bénéficiaire est invité à se munir de...
	foreach( (array)Hash::get( $options, 'Autre.Ficheprescription93.documentbeneffp93_id' ) as $documentbeneffp93_id ) {
		echo $this->Observer->disableFieldsOnCheckbox(
			"Documentbeneffp93.Documentbeneffp93.{$documentbeneffp93_id}",
			'Ficheprescription93.documentbeneffp93_autre',
			false,
			false
		);
	}

	/*echo $this->Observer->dependantSelect(
		array(
			'Ficheprescription93.structurereferente_id' => 'Ficheprescription93.referent_id',
			'Thematiquefp93.type' => 'Categoriefp93.thematiquefp93_id',
			'Categoriefp93.thematiquefp93_id' => 'Filierefp93.categoriefp93_id',
			'Filierefp93.categoriefp93_id' => 'Actionfp93.filierefp93_id',
			'Actionfp93.filierefp93_id' => 'Actionfp93.prestatairefp93_id',
			'Actionfp93.prestatairefp93_id' => 'Ficheprescription93.actionfp93_id',
		)
	);*/

	// Personne reçue
	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.personne_recue',
		array(
			'Ficheprescription93.motifnonreceptionfp93_id',
			'Ficheprescription93.personne_nonrecue_autre'
		),
		array( null, '', '1' ),
		true
	);

	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.motifnonreceptionfp93_id',
		array(
			'Ficheprescription93.personne_nonrecue_autre'
		),
		(array)Hash::get( $options, 'Autre.Ficheprescription93.motifnonreceptionfp93_id' ),
		false
	);

	// Personne retenue
	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.personne_retenue',
		array(
			'Ficheprescription93.motifnonretenuefp93_id',
			'Ficheprescription93.personne_nonretenue_autre',
		),
		array( null, '', '1' ),
		true
	);

	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.motifnonretenuefp93_id',
		array(
			'Ficheprescription93.personne_nonretenue_autre'
		),
		(array)Hash::get( $options, 'Autre.Ficheprescription93.motifnonretenuefp93_id' ),
		false
	);

	// Personne souhaite intégrer
	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.personne_souhaite_integrer',
		array(
			'Ficheprescription93.motifnonsouhaitfp93_id',
			'Ficheprescription93.personne_nonsouhaite_autre',
		),
		array( null, '', '1' ),
		true
	);

	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.motifnonsouhaitfp93_id',
		array(
			'Ficheprescription93.personne_nonsouhaite_autre'
		),
		(array)Hash::get( $options, 'Autre.Ficheprescription93.motifnonsouhaitfp93_id' ),
		false
	);

	// Personne a intégré
	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.personne_a_integre',
		array(
			'Ficheprescription93.motifnonintegrationfp93_id',
			'Ficheprescription93.personne_nonintegre_autre',
		),
		array( null, '', '1' ),
		true
	);
	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.personne_a_integre',
		array(
			'Ficheprescription93.personne_date_integration.day',
			'Ficheprescription93.personne_date_integration.month',
			'Ficheprescription93.personne_date_integration.year',
		),
		array( null, '', '0' ),
		true
	);

	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.motifnonintegrationfp93_id',
		array(
			'Ficheprescription93.personne_date_integration',
		),
		(array)Hash::get( $options, 'Autre.Ficheprescription93.motifnonintegrationfp93_id' ),
		false
	);

	echo $this->Observer->disableFieldsOnValue(
		'Ficheprescription93.motifnonintegrationfp93_id',
		array(
			'Ficheprescription93.personne_nonintegre_autre'
		),
		(array)Hash::get( $options, 'Autre.Ficheprescription93.motifnonintegrationfp93_id' ),
		false
	);

	echo $this->Ajax2->autocomplete(
		'Ficheprescription93.numconvention',
		array(
			'url' => array( 'action' => 'ajax_ficheprescription93_numconvention' )
		)
	);

	echo $this->Ajax2->updateDivOnFieldsChange(
		'CoordonneesPrescripteur',
		array( 'action' => 'ajax_prescripteur' ),
		array(
			'Ficheprescription93.structurereferente_id',
			'Ficheprescription93.referent_id',
		)
	);
?>
<script type="text/javascript">
//<![CDATA[
	// TODO: mettre en fonction Ajax et mettre dans le moteur de recherche
	// TODO: gérer le autocomplete
	function foo( changed ) {
		new Ajax.Request(
			'/webrsa/WebRSA-trunk/fichesprescriptions93/ajax_action', // TODO: url en paramètre
			{
				method: 'post',
				parameters: {
					'data[Field][changed]': changed,
					// Valeurs renvoyées
					'data[Ficheprescription93][numconvention]': $F( 'Ficheprescription93Numconvention' ),
					'data[Thematiquefp93][type]': $F( 'Thematiquefp93Type' ),
					'data[Categoriefp93][thematiquefp93_id]': $F( 'Categoriefp93Thematiquefp93Id' ),
					'data[Filierefp93][categoriefp93_id]': $F( 'Filierefp93Categoriefp93Id' ),
					'data[Actionfp93][filierefp93_id]': $F( 'Actionfp93Filierefp93Id' ),
					'data[Actionfp93][prestatairefp93_id]': $F( 'Actionfp93Prestatairefp93Id' ),
					'data[Ficheprescription93][actionfp93_id]': $F( 'Ficheprescription93Actionfp93Id' ),
				},
				onSuccess: function( response ) {
					var json = response.responseText.evalJSON();

					if( json.success ) {
						// Pour chacun des champs
						for( path in json.fields ) {
							var field = json.fields[path];

							$( field['id'] ).value = field['value'];

							if( field['type'] == 'select' ) {
								var select = new Element( 'select' );
								$( select ).insert( { bottom: new Element( 'option', { 'value': '' } ) } );

								// Options
								if( typeof field['options'] == 'object' ) {
									for( primaryKey in field['options'] ) {
										var option = Element( 'option', { 'value': primaryKey } ).update( field['options'][primaryKey] );
										$( select ).insert( { bottom: option } );
									}
								}

								$( field['id'] ).update( $( select ).innerHTML );
							}
						}
					}
					else {
						// TODO -> une erreur à afficher proprement
					}
				}
			}
		);
	}

	// TODO: désactiver l'autre
	// Event.observe( $( 'Ficheprescription93Numconvention' ), 'change', function() { foo( 'Ficheprescription93.numconvention' ) } );
	Event.observe( $( 'Thematiquefp93Type' ), 'change', function() { foo( 'Thematiquefp93.type' ) } );
	Event.observe( $( 'Categoriefp93Thematiquefp93Id' ), 'change', function() { foo( 'Categoriefp93.thematiquefp93_id' ) } );
	Event.observe( $( 'Filierefp93Categoriefp93Id' ), 'change', function() { foo( 'Filierefp93.categoriefp93_id' ) } );
	Event.observe( $( 'Actionfp93Filierefp93Id' ), 'change', function() { foo( 'Actionfp93.filierefp93_id' ) } );
	Event.observe( $( 'Actionfp93Prestatairefp93Id' ), 'change', function() { foo( 'Actionfp93.prestatairefp93_id' ) } );
	Event.observe( $( 'Ficheprescription93Actionfp93Id' ), 'change', function() { foo( 'Ficheprescription93.actionfp93_id' ) } );

	// TODO: en cas d'edit, voir les valeurs du formulaire
	/*document.observe( 'dom:loaded', function() {
		foo( 'Thematiquefp93.type' );
		foo( 'Categoriefp93.thematiquefp93_id' );
		foo( 'Filierefp93.categoriefp93_id' );
		foo( 'Actionfp93.filierefp93_id' );
		foo( 'Actionfp93.prestatairefp93_id' );
		foo( 'Ficheprescription93.actionfp93_id' );
	} );*/
//]]>
</script>