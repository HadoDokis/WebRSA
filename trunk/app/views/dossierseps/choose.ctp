<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la séance de l\'EP « %s » du %s',
			$seanceep['Ep']['name'],
			$locale->date( 'Locale->datetime', $seanceep['Seanceep']['dateseance'] )
		);
	?>
</h1>

<?php
	echo $default->index(
		$dossierseps,
		array(
			'Dossierep.chosen' => array( 'input' => 'checkbox' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Dossierep.created',
			'Dossierep.themeep',
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id' )
		)
	);
// 	debug( $dossierseps );
?>