<?php
	Configure::write(
		'Filtresdefaut.Dossierspcgs66_search',
		array(
			'Dossier' => array(
				'dernier' => '1'
			)
		)
	);
	Configure::write(
		'Filtresdefaut.Dossierspcgs66_search_gestionnaire',
		array(
			'Dossier' => array(
				'dernier' => '1'
			)
		)
	);

	Configure::write(
		'ConfigurableQueryDossierspcgs66',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.datereceptionpdo',
					'Poledossierpcg66.name',
					'User.nom_complet',
					'Dossierpcg66.nbpropositions',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.org_id',
					'Decisiondossierpcg66.datetransmissionop',
					'Traitementpcg66.datereception',
					'Decisionpdo.libelle',
					'Traitementpcg66.situationpdo_id',
					'Traitementpcg66.statutpdo_id',
					'Fichiermodule.nb_fichiers_lies',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
					'/Dossierspcgs66/edit/#Dossierpcg66.id#' => array( 'class' => 'edit' ),
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Dossier.matricule',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'Dossierpcg66.originepdo_id',
				'Dossierpcg66.typepdo_id',
				'Dossierpcg66.datereceptionpdo',
				'Dossierpcg66.poledossierpcg66_id',
				'User.nom_complet',
				'Decisionpdo.libelle',
				'Dossierpcg66.nbpropositions',
				'Dossierpcg66.etatdossierpcg',
				'Decisiondossierpcg66.org_id',
				'Decisiondossierpcg66.datetransmissionop',
				'Traitementpcg66.datereception',
				'Traitementpcg66.situationpdo_id',
				'Traitementpcg66.statutpdo_id',
				'Fichiermodule.nb_fichiers_lies',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Familleromev3.name',
				'Domaineromev3.name',
				'Metierromev3.name',
				'Appellationromev3.name',
				'Categoriemetierromev2.code',
				'Categoriemetierromev2.name'
			),
			'search_gestionnaire' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Traitementpcg66.dateecheance',
					'Dossierpcg66.user_id',
					'Dossierpcg66.nbpropositions',
					'Personnepcg66.nbtraitements',
					'Dossierpcg66.listetraitements',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.decisionpdo_id',
					'Traitementpcg66.situationpdo_id',
					'Traitementpcg66.statutpdo_id',
					'Fichiermodule.nb_fichiers_lies',
					'Dossier.locked',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
					'/Dossierspcgs66/edit/#Dossierpcg66.id#' => array( 'class' => 'edit' ),
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Dossier.matricule',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv_gestionnaire' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'Dossierpcg66.originepdo_id',
				'Dossierpcg66.typepdo_id',
				'Dossierpcg66.datereceptionpdo',
				'User.nom_complet',
				'Dossierpcg66.nbpropositions',
				'Personnepcg66.nbtraitements',
				'Dossierpcg66.listetraitements',
				'Dossierpcg66.etatdossierpcg',
				'Fichiermodule.nb_fichiers_lies',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			),
			'cohorte_enattenteaffectation' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossierpcg66.datereceptionpdo',
					'Typepdo.libelle',
					'Originepdo.libelle',
					'Dossierpcg66.orgpayeur',
					'Serviceinstructeur.lib_service',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'cohorte_atransmettre' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossierpcg66.datereceptionpdo',
					'Typepdo.libelle',
					'Originepdo.libelle',
					'Dossierpcg66.orgpayeur',
					'Serviceinstructeur.lib_service',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			)
		)
	);
?>