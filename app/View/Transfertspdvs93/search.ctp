<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->actions(
		array(
			'/Transfertspdvs93/search/#toggleform' => array(
				'onclick' => '$(\'Transfertspdvs93SearchForm\').toggle(); return false;',
				'class' => 'search'
			),
		)
	);

	echo $this->Xform->create( null, array( 'id' => 'Transfertspdvs93SearchForm', 'class' => ( !empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Allocataires->blocDossier( array( 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire( array( 'options' => $options ) );

	// Début spécificités transfert PDV
	echo '<fieldset><legend>' . __m( 'Orientstruct.search' ) . '</legend>';
	//echo $this->Search->date( 'Search.NvOrientstruct.date_valid', array( 'legend' => 'Foo' ) );
	echo $this->SearchForm->dateRange( 'Search.NvOrientstruct.date_valid', array( 'legend' => __m( 'Search.NvOrientstruct.date_valid' ) ) );
	echo $this->Form->input( 'Search.Orientstruct.typeorient_id', array( 'label' => __m( 'Search.Orientstruct.typeorient_id' ), 'type' => 'select', 'empty' => true, 'options' => $options['Orientstruct']['typeorient_id'] ) );
	echo $this->Form->input( 'Search.NvOrientstruct.structurereferente_id', array( 'label' => __m( 'Search.NvOrientstruct.structurereferente_id' ), 'type' => 'select', 'empty' => true, 'options' => $options['Orientstruct']['structurereferente_id'] ) );
	echo '</fieldset>';
	// Fin spécificités transfert PDV

	echo $this->Allocataires->blocReferentparcours( array( 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'options' => $options ) );

	echo $this->Xform->end( 'Search' );

	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'modelName' => 'Dossier' ) );
	}
?>