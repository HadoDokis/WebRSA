<?php
	echo $this->Default3->csv(
		$results,
		array(
			'Dossier.numdemrsa' => array( 'domain' => 'dossier' ),
			'Dossier.matricule' => array( 'domain' => 'dossier' ),
			'Adresse.codepos',
			'Adresse.nomcom',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Prestation.rolepers',
			'Transfertpdv93.created' => array( 'type' => 'date' ),
			'VxStructurereferente.lib_struc',
			'NvStructurereferente.lib_struc',
			'Structurereferenteparcours.lib_struc' => array( 'domain' => 'search_plugin' ),
			'Referentparcours.nom_complet' => array( 'domain' => 'search_plugin' )
		),
		array(
			'options' => $options
		)
	);
?>