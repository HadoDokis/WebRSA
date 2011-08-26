<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Dossier.matricule',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Nonrespectsanctionep93.origine',
			'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) ),
			'trClass' => $trClass,
		)
	);
?>