<?php
	echo $xhtml->tag( 'h1', $this->pageTitle = 'Dossiers relancés' );

	echo $default2->search(
		array(
			'Nonrespectsanctionep93.mode' => array(
				'type' => 'select',
				'options' => array(
					'encours' => 'En cours de traitement',
					'traite' => 'Finalisés'
				)
			),
			'Dossierep.seanceep_id' => array( 'empty' => true, 'domain' => 'nonrespectsanctionep93' )
		),
		array(
			'options' => $options
		)
	);
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue( 'SearchNonrespectsanctionep93Mode', [ 'SearchDossierepSeanceepId' ], 'traite', false );
	} );
</script>