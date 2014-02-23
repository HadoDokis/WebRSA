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
	echo '<fieldset><legend>'.__d( 'fichesprescriptions93', 'Search.Ficheprescription93' ).'</legend>';
	echo $this->Xform->input( 'Search.Thematiquefp93.type', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Thematiquefp93.type' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Categoriefp93.thematiquefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Categoriefp93.thematiquefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Filierefp93.categoriefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Filierefp93.categoriefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Actionfp93.filierefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Actionfp93.filierefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Actionfp93.prestatairefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Actionfp93.prestatairefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.actionfp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.actionfp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.rdvprestataire_date', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.statut', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.statut' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
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
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) )
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
	echo $this->Allocataires->SearchForm->jsObserveDependantSelect(
		array(
			'Search.Thematiquefp93.type' => 'Search.Categoriefp93.thematiquefp93_id',
			'Search.Categoriefp93.thematiquefp93_id' => 'Search.Filierefp93.categoriefp93_id',
			'Search.Filierefp93.categoriefp93_id' => 'Search.Actionfp93.filierefp93_id',
			'Search.Actionfp93.filierefp93_id' => 'Search.Actionfp93.prestatairefp93_id',
			'Search.Actionfp93.prestatairefp93_id' => 'Search.Ficheprescription93.actionfp93_id',
		)
	);
?>