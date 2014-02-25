<?php
	echo $this->Default3->csv(
		$results,
		array(
			'Dossier.numdemrsa',
			'Dossier.dtdemrsa',
			'Dossier.matricule',
			'Personne.nom_complet',
			'Prestation.rolepers',
			'Adresse.locaadr',
			'Ficheprescription93.statut',
			'Actionfp93.name',
		),
		array(
			'options' => $options
		)
	);
?>