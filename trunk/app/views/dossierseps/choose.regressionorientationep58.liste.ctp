<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossierep.id' => array( 'label' => 'N° de dossier' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Dossierep.created',
			'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'actions' => array( 'Personnes::view' ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) ),
			'trClass' => $trClass,
		)
	);
?>