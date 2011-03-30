<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la séance de l\'EP « %s » du %s',
			$commissionep['Ep']['name'],
			$locale->date( 'Locale->datetime', $commissionep['Commissionep']['dateseance'] )
		);
	?>
</h1>

<?php
		
	if ( isset( $themeEmpty ) && $themeEmpty == true ) {
		echo '<p class="notice">Veuillez attribuer des thème à l\'EP gérant la commission avant.</p>';
	}
	else {
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
				'hidden' => array( 'Dossierep.id' ),
				'paginate' => 'Dossierep'
			)
		);
// 	debug( $dossierseps );
	}

    echo $default->button(
		'back',
        array(
        	'controller' => 'commissionseps',
        	'action'     => 'view',
        	$commissionep_id
        ),
        array(
        	'id' => 'Back'
        )
	);
?>