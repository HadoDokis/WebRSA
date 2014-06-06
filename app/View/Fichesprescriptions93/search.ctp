<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->actions(
		array(
			'/Fichesprescriptions93/search/#toggleform' => array(
				'onclick' => '$(\'Fichesprescriptions93SearchForm\').toggle(); return false;'
			),
		)
	);

	echo $this->Xform->create( 'Search', array( 'id' => 'Fichesprescriptions93SearchForm' ) );

	echo $this->Allocataires->blocDossier( array( 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire( array( 'options' => $options ) );

	// Début spécificités fiche de prescription
	echo $this->Xform->input( 'Search.Ficheprescription93.exists', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	echo '<fieldset id="SpecificitesFichesprescriptions93"><legend>'.__d( 'fichesprescriptions93', 'Search.Ficheprescription93' ).'</legend>';
	echo $this->Xform->input( 'Search.Ficheprescription93.numconvention', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.typethematiquefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.typethematiquefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.thematiquefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.categoriefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.filierefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.prestatairefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.actionfp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.actionfp93', array( 'type' => 'text', 'domain' => 'fichesprescriptions93' ) );

	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.rdvprestataire_date', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.date_transmission', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.date_retour', array( 'domain' => 'fichesprescriptions93' ) );

	echo $this->Xform->input( 'Search.Ficheprescription93.statut', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.statut' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );

	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_retour', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );

	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.df_action', array( 'domain' => 'fichesprescriptions93' ) );

	$paths = array(
		'Ficheprescription93.benef_retour_presente',
		'Ficheprescription93.personne_recue',
		'Ficheprescription93.personne_retenue',
		'Ficheprescription93.personne_a_integre',
	);
	foreach( $paths as $path ) {
		echo $this->Xform->input( "Search.{$path}", array( 'type' => 'select', 'options' => (array)Hash::get( $options, $path ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	}

	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_bilan_mi_parcours', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_bilan_final', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );

	echo '</fieldset>';
	// Fin spécificités fiche de prescription

	echo $this->Allocataires->blocReferentparcours( array( 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'options' => $options ) );

	echo $this->Xform->end( 'Search' );

	if( isset( $results ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		App::uses( 'SearchProgressivePagination', 'Search.Utility' );

		echo $this->Default3->index(
			$results,
			array(
				'Dossier.matricule',
				'Personne.nom_complet',
				'Adresse.locaadr',
				'Ficheprescription93.statut',
				'Actionfp93.name',
				'Dossier.locked' => array( 'type' => 'boolean' ),
				'/Fichesprescriptions93/index/#Personne.id#',
			),
			array(
				'options' => $options,
				'format' => __( SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ) )
			)
		);
	}

//	debug( $options );
?>
<?php if( isset( $results ) ):?>
<ul class="actionMenu">
	<li><?php
		echo $this->Xhtml->exportLink(
			'Télécharger le tableau',
			array( 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
			( $this->Permissions->check( $this->request->params['controller'], 'exportcsv' ) && count( $results ) > 0 )
		);
	?></li>
</ul>
<?php endif;?>

<?php
	echo $this->Observer->disableFieldsetOnValue(
		'Search.Ficheprescription93.exists',
		'SpecificitesFichesprescriptions93',
		array( null, '1' ),
		true,
		true
	);

	// Catalogue PDI
	echo $this->Observer->disableFieldsOnValue(
		'Search.Ficheprescription93.typethematiquefp93_id',
		array(
			'Search.Ficheprescription93.prestatairehorspdifp93_id',
			'Search.Ficheprescription93.actionfp93',
		),
		array( null, '', 'pdi' ),
		true,
		true
	);

	// Catalogue Hors PDI
	echo $this->Observer->disableFieldsOnValue(
		'Search.Ficheprescription93.typethematiquefp93_id',
		array(
			'Search.Ficheprescription93.numconvention',
			'Search.Ficheprescription93.prestatairefp93_id',
			'Search.Ficheprescription93.actionfp93_id',
		),
		array( 'horspdi' ),
		true,
		true
	);

	echo $this->Ajax2->observe(
		array(
			'Search.Ficheprescription93.numconvention' => array( 'event' => 'keyup' ),
			'Search.Ficheprescription93.typethematiquefp93_id',
			'Search.Ficheprescription93.thematiquefp93_id',
			'Search.Ficheprescription93.categoriefp93_id',
			'Search.Ficheprescription93.filierefp93_id',
			'Search.Ficheprescription93.prestatairefp93_id',
			'Search.Ficheprescription93.actionfp93_id',
		),
		array(
			'url' => array( 'action' => 'ajax_action' ),
			'prefix' => 'Search',
			'onload' => !empty( $this->request->data )
		)
	);
?>