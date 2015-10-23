<?php $this->start( 'custom_search_filters' );?>
<?php
	echo '<fieldset><legend>' . __m( 'Orientstruct.search' ) . '</legend>';
	//echo $this->Search->date( 'Search.NvOrientstruct.date_valid', array( 'legend' => 'Foo' ) );
	echo $this->SearchForm->dateRange( 'Search.NvOrientstruct.date_valid', array( 'legend' => __m( 'Search.NvOrientstruct.date_valid' ) ) );
	echo $this->Form->input( 'Search.Orientstruct.typeorient_id', array( 'label' => __m( 'Search.Orientstruct.typeorient_id' ), 'type' => 'select', 'empty' => true, 'options' => $options['Orientstruct']['typeorient_id'] ) );
	echo $this->Form->input( 'Search.NvOrientstruct.structurereferente_id', array( 'label' => __m( 'Search.NvOrientstruct.structurereferente_id' ), 'type' => 'select', 'empty' => true, 'options' => $options['Orientstruct']['structurereferente_id'] ) );
	echo '</fieldset>';
?>
<?php $this->end();?>

<?php
	echo $this->element(
		'ConfigurableQuery/search',
		array(
			'custom' => $this->fetch( 'custom_search_filters' ),
			'modelName' => 'Dossier'
		)
	);
?>