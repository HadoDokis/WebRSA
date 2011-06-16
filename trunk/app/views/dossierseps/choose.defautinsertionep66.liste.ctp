<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Dossierep.created',
// 			'Dossierep.themeep',
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'actions' => array(
				'Personnes::view',
			),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) )
		)
	);
?>