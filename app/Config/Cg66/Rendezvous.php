<?php
	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * rendez-vous:
	 *	- lignes du tableau: Rendezvous.search.fields
	 *	- info-bulle du tableau: Rendezvous.search.innerTable
	 *	- export CSV: Rendezvous.exportcsv
	 *
	 * @var array
	 */
	// FIXME: champs export CSV
	Configure::write(
		'Rendezvous',
		array(
			'search' => array(
				'fields' => array(
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Typerdv.libelle',
					'Rendezvous.daterdv',
					'Rendezvous.heurerdv',
					'Statutrdv.libelle',
					// FIXME: caché dans le title, attention au thead
					/*'Dossier.numdemrsa' => array(
						'condition' => false
					),*/
					'Canton.canton',
					'/Rendezvous/index/#Rendezvous.personne_id#',
					'/Rendezvous/impression/#Rendezvous.id#'
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				)
			),
			'exportcsv' => array(
//				'Entretien.dateentretien',
//				'Personne.nom_complet',
//				'Dossier.matricule',
//				'Adresse.numvoie',
//				'Adresse.libtypevoie',
//				'Adresse.nomvoie',
//				'Adresse.complideadr',
//				'Adresse.compladr',
//				'Adresse.codepos',
//				'Adresse.nomcom',
//				'Structurereferente.lib_struc',
//				'Referent.nom_complet',
//				'Entretien.typeentretien',
//				'Objetentretien.name',
//				'Entretien.arevoirle' => array(
//					'format' => '%B %Y'
//				),
//				'Referentparcours.nom_complet',
//				'Structurereferenteparcours.lib_struc'
			)
		)
	);
?>