<h1><?php echo $this->pageTitle = 'Sélection des allocataires non inscrits à Pôle Emploi';?></h1>
<?php
	echo $default2->index(
		$personnes,
		array(
			'Orientstruct.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean' ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Orientstruct.date_valid',
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Orientstruct.personne_id',
				'Orientstruct.id'
			),
			'paginate' => 'Personne'
		)
	);

// 	debug( $personnes );
?>