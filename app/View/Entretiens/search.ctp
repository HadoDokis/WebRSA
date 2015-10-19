<?php $this->start( 'custom_search_filters' );?>
<fieldset>
	<legend>Filtrer par Entretiens</legend>
	<?php
		echo $this->Default2->subform(
			array(
				'Search.Entretien.arevoirle' => array( 'label' => __d( 'entretien', 'Entretien.arevoirle' ), 'type' => 'date', 'dateFormat' => 'MY', 'empty' => true, 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
				'Search.Entretien.structurereferente_id' => array( 'label' => __d( 'entretien', 'Entretien.structurereferente_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['structurereferente_id'] ),
				'Search.Entretien.referent_id' => array( 'label' => __d( 'entretien', 'Entretien.referent_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['referent_id']  ),
				'Search.Entretien.dateentretien' => array( 'type' => 'checkbox' )
			),
			array(
				'options' => $options
			)
		);

		echo $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', __m( 'Search.Entretien.dateentretien' ) )
				.$this->Default2->subform(
				array(
					'Search.Entretien.dateentretien_from' => array( 'label' => __m( 'Search.Entretien.dateentretien_from' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
					'Search.Entretien.dateentretien_to' => array( 'label' => __m( 'Search.Entretien.dateentretien_to' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
				),
				array(
					'options' => $options
				)
			)
		);
	?>
</fieldset>
<?php $this->end();?>

<?php
	echo $this->element(
		'ConfigurableQuery/search',
		array(
			'custom' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => 'exportcsv' )
		)
	);
?>