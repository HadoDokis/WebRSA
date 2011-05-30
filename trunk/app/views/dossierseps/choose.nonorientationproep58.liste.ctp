<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossierep.chosen' => array( 'input' => 'checkbox' ),
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
			'paginate' => 'Dossierep',
			'actions' => array( 'Personnes::view' ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer'
		)
	);
?>