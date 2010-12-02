<?php
	if( empty( $this->pageTitle ) || $this->pageTitle == 'Saisinesepsreorientsrs93::index' ) {
		$this->pageTitle = 'Demandes de réorientation 93';
		echo $xhtml->tag( 'h1', $this->pageTitle );
	}

	echo $default2->search(
		array(
			'Saisineepreorientsr93.mode' => array(
				'type' => 'select',
				'options' => array(
					'encours' => 'En cours de traitement',
					'traite' => 'Finalisés'
				)
			),
			'Dossierep.seanceep_id' => array( 'empty' => true, 'domain' => 'saisineepreorientsr93' )
		),
		array(
			'options' => $options
		)
	);
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue( 'SearchSaisineepreorientsr93Mode', [ 'SearchDossierepSeanceepId' ], 'traite', false );
	} );
</script>