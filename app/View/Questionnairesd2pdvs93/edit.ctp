<?php
	echo $this->Default3->titleForLayout( $personne );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Questionnaired2pdv93.id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.personne_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.structurereferente_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.questionnaired1pdv93_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.situationaccompagnement' => array(
				'options' => $options['Questionnaired2pdv93']['situationaccompagnement'],
				'empty' => true,
				'required' => true
			),
			'Questionnaired2pdv93.sortieaccompagnementd2pdv93_id' => array(
				'options' => $options['Questionnaired2pdv93']['sortieaccompagnementd2pdv93_id'],
				'empty' => true,
				'required' => true
			),
			'Questionnaired2pdv93.chgmentsituationadmin' => array(
				'options' => $options['Questionnaired2pdv93']['chgmentsituationadmin'],
				'empty' => true,
				'required' => true
			),
		),
		array(
			'buttons' => array( 'Validate', 'Cancel' )
		)
	);
?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue(
			'Questionnaired2pdv93Situationaccompagnement',
			[ 'Questionnaired2pdv93Sortieaccompagnementd2pdv93Id' ],
			[ 'sortie_obligation' ],
			false,
			false
		);
		observeDisableFieldsOnValue(
			'Questionnaired2pdv93Situationaccompagnement',
			[ 'Questionnaired2pdv93Chgmentsituationadmin' ],
			[ 'changement_situation' ],
			false,
			false
		);
	} );
</script>