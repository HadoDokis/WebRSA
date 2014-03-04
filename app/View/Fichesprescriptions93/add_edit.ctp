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
				'Ficheprescription93.objet',
			),
			array(
				'options' => $options,
			)
		)
	);

	// TODO: Cadre bénéficiaire

	// Cadre prestataire / partenaire
	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', __d( $this->request->params['controller'], 'Ficheprescription93.Prestataire' ) )
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
				// TODO: duree_action
			),
			array(
				'options' => $options,
			)
		)
	);

	echo $this->Default3->DefaultForm->buttons( array( 'Validate', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();

//	debug( get_defined_vars() );
?>
<?php
	echo $this->Allocataires->SearchForm->jsObserveDependantSelect(
		array(
			'Ficheprescription93.structurereferente_id' => 'Ficheprescription93.referent_id',
			'Thematiquefp93.type' => 'Categoriefp93.thematiquefp93_id',
			'Categoriefp93.thematiquefp93_id' => 'Filierefp93.categoriefp93_id',
			'Filierefp93.categoriefp93_id' => 'Actionfp93.filierefp93_id',
			'Actionfp93.filierefp93_id' => 'Actionfp93.prestatairefp93_id',
			'Actionfp93.prestatairefp93_id' => 'Ficheprescription93.actionfp93_id',
		)
	);
?>
<?php
	echo $this->Ajax2->autocomplete(
		'Ficheprescription93.numconvention',
		array(
			'url' => array( 'action' => 'ajax_ficheprescription93_numconvention' )
		)
	);
?>