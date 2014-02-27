<?php
	echo $this->Default3->csv(
		$results,
		array(
			'Dossier.matricule', //'Numero CAF/MSA',
			'Personne.nom_complet', //'Nom / Prénom du demandeur',
			'Personne.dtnai', //'Date de naissance du demandeur',
			//'Adresse',
			'Adresse.numvoie',
			'Adresse.typevoie',
			'Adresse.nomvoie',
			'Adresse.complideadr',
			'Adresse.compladr',
			'Adresse.codepos',
			'Adresse.locaadr',
			'Conjoint.nom_complet', //'Nom / Prénom du conjoint',
			'Dossier.dtdemrsa', //'Date ouverture de droits',
			// TODO //'Ref. charge de l\'evaluation',
			'Orientstruct.date_valid', //'Date orientation (COV)',
			'Orientstruct.rgorient', //'Rang orientation (COV)',
			'Referentparcours.nom_complet', //'Referent unique',
			'Contratinsertion.dd_ci', //'Date debut (CER)',
			'Contratinsertion.df_ci', //'Date fin (CER)',
			'Contratinsertion.rg_ci', //'Rang (CER)',
			'Historiqueetatpe.etat', //'Dernier état Pole Emploi',
			'Historiqueetatpe.date', //'Date inscription Pole Emploi',
			'Commissionep.dateseance', //'Date (EP)',
			'Dossierep.themeep', //'Motif (EP)'
		),
		array(
			'options' => $options
		)
	);
?>