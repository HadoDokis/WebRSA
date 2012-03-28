<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossiercov58.id' => array( 'label' => 'N° de dossier' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Dossiercov58.created',
			'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			'Passagecov58.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossiercov58.id', 'Passagecov58.id' ),
			'paginate' => Inflector::classify( $theme ),
			'actions' => array( 'Personnes::view' ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array_merge( array( 'Choose.theme' => array( 'value' => $theme ), ), $dossiersIds ),
			'trClass' => $trClass,
		)
	);
?>