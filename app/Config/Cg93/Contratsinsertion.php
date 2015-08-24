<?php
	Configure::write(
		'Filtresdefaut.Contratsinsertion_search',
		array(
			'Dossier' => array(
				'dernier' => true
			)
		)
	);

	Configure::write(
		'ConfigurableQueryContratsinsertion',
		array(
			'search' => array(
				'fields' => array (
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Referent.nom_complet',
					'Dossier.matricule',
					'Typeorient.lib_type_orient',
					'Contratinsertion.created' => array( 'type' => 'date' ),
					'Cer93.duree',
					'Contratinsertion.rg_ci',
					'Cer93.positioncer',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.forme_ci',
					'Contratinsertion.df_ci',
					'/Cers93/index/#Contratinsertion.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Prestation.rolepers',
					'Situationdossierrsa.etatdosrsa',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array( 'Contratinsertion.df_ci' )
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Situationdossierrsa.etatdosrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossier.matricule',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Typeorient.lib_type_orient',
				'Referent.nom_complet',
				'Structurereferente.lib_struc',
				'Contratinsertion.forme_ci',
				'Contratinsertion.dd_ci' => array( 'type' => 'date' ),
				'Cer93.duree',
				'Contratinsertion.df_ci' => array( 'type' => 'date' ),
				'Cer93.positioncer',
				'Contratinsertion.datevalidation_ci' => array( 'type' => 'date' ),
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				// FIXME: traductions
				// 1. Expériences professionnelles significatives
				// 1.1 Codes INSEE
				'Secteuractiexppro.name',
				'Metierexerceexppro.name',
				// 1.2 Codes ROME v.3
				'Familleexppro.name',
				'Domaineexppro.name',
				'Metierexppro.name',
				'Appellationexppro.name',
				// 2. Emploi trouvé
				// 2.1 Codes INSEE
				'Secteuracti.name',
				'Metierexerce.name',
				// 2.2 Codes ROME v.3
				'Familleemptrouv.name',
				'Domaineemptrouv.name',
				'Metieremptrouv.name',
				'Appellationemptrouv.name',
				// 3. Votre contrat porte sur
				// 3.1 Sujets, ... du CER
				'Sujetcer93.name',
				'Cer93Sujetcer93.commentaireautre',
				'Soussujetcer93.name',
				'Cer93Sujetcer93.autresoussujet',
				'Valeurparsoussujetcer93.name',
				'Cer93Sujetcer93.autrevaleur',
				// 3.2 Codes ROME v.3
				'Famillesujet.name',
				'Domainesujet.name',
				'Metiersujet.name',
				'Appellationsujet.name'
			)
		)
	);
?>