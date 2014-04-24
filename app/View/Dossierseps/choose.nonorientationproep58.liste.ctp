<?php
// debug( $dossiers[$theme] );
	echo $this->Default2->index(
		$dossiers[$theme],
		array(
			'Dossierep.id' => array( 'label' => 'N° de dossier' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Dossier.matricule' => array( 'label' => 'N° allocataire' ),
			'Adresse.locaadr',
			'Dossierep.created',
			'Cov58.datecommission' => array( 'label' => 'Proposition validée en COV le' ),
			'Structurereferente.lib_struc' => array( 'label' => 'Structure référente' ),
// 			'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'actions' => array( 'Personnes::view' ),
			'id' => $theme,
			'labelcohorte' => array(
				'Enregistrer',
				'Annuler' => array( 'name' => 'Cancel' ),
			),
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) ),
			'trClass' => $trClass,
		)
	);
?>