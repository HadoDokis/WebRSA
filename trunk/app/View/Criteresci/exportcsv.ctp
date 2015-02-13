<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$canton = '';
	if( Configure::read('Cg.departement') == 66 ) {
		$canton = 'Canton';
	}

	// Ligne d'en-tête
	$row = array(
		'N° Dossier',
		__d( 'dossier', 'Dossier.matricule' ),
		'Etat du droit',
		'Qualité',
		'Nom',
		'Prénom',
		__d( 'dossier', 'Dossier.matricule' ),
		'Numéro de voie',
		'Type de voie',
		'Nom de voie',
		'Complément adresse 1',
		'Complément adresse 2',
		'Code postal',
		'Commune',
		'Type d\'orientation',
		'Référent',
		'Service référent',
		'Type de contrat',
		'Date début contrat',
		'Durée',
		'Date fin contrat',
		'Décision et date validation',
		'Action prévue',
		__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
		__d( 'search_plugin', 'Referentparcours.nom_complet' ),
	);

	if( Configure::read( 'Cg.departement' ) == 58 ) {
		$row = array_merge(
			$row,
			array(
				__d( 'personne', 'Personne.etat_dossier_orientation' )
			)
		);
	}
	else if( Configure::read( 'Cg.departement' ) == 93 ) {
		$row = array_merge(
			$row,
			array(
				// Expériences professionnelles significatives
				__d( 'metierexerce', 'Metierexerce.name' ),
				__d( 'secteuracti', 'Secteuracti.name' ),
				// Emploi trouvé (ROME v.3)
				__d( 'cers93', 'Emptrouvromev3.familleromev3_id' ),
				__d( 'cers93', 'Emptrouvromev3.domaineromev3_id' ),
				__d( 'cers93', 'Emptrouvromev3.metierromev3_id' ),
				__d( 'cers93', 'Emptrouvromev3.appellationromev3_id' ),
				// Votre contrat porte sur
				__d( 'sujetcer93', 'Sujetcer93.name' ),
				'Autre, précisez',
				__d( 'soussujetcer93', 'Soussujetcer93.name' ),
				'Autre, précisez',
				__d( 'valeurparsoussujetcer93', 'Valeurparsoussujetcer93.name' ),
				'Autre, précisez',
			)
		);
	}

	$this->Csv->addRow( $row );

	// Lignes de résultats
	foreach( $contrats as $contrat ) {
		$lib_type_orient = Hash::get( $contrat, 'Typeorient.lib_type_orient' );

		$duree = Hash::get( $contrat, 'Cer93.duree' );
		if( empty( $duree ) ) {
			$duree = Set::enum( Hash::get( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag );
		}
		else {
			$duree = "{$duree} mois";
		}

		if( Configure::read( 'Cg.departement' ) == 93 ) {
			$decision = Hash::get( $options['Cer93']['positioncer'], Hash::get( $contrat, 'Cer93.positioncer' ) )
				.( Hash::get( $contrat, 'Contratinsertion.decision_ci' ) == 'V' ? ' '.$this->Locale->date( 'Date::short', Hash::get( $contrat, 'Contratinsertion.datedecision' ) ) : '' );
		}
		else {
			$decision = value( $decision_ci, Hash::get( $contrat, 'Contratinsertion.decision_ci' ) ).' '.date_short( Hash::get( $contrat, 'Contratinsertion.datevalidation_ci' ) );
		}

		$row = array(
			Hash::get( $contrat, 'Dossier.numdemrsa' ),
			Hash::get( $contrat, 'Dossier.matricule' ),
			value( $etatdosrsa, Hash::get( $contrat, 'Situationdossierrsa.etatdosrsa' ) ),
			value( $qual, Hash::get( $contrat, 'Personne.qual' ) ),
			Hash::get( $contrat, 'Personne.nom' ),
			Hash::get( $contrat, 'Personne.prenom' ),
			Hash::get( $contrat, 'Dossier.matricule' ),
			Hash::get( $contrat, 'Adresse.numvoie' ),
			Hash::get( $contrat, 'Adresse.libtypevoie' ),
			Hash::get( $contrat, 'Adresse.nomvoie' ),
			Hash::get( $contrat, 'Adresse.complideadr' ),
			Hash::get( $contrat, 'Adresse.compladr' ),
			Hash::get( $contrat, 'Adresse.codepos' ),
			Hash::get( $contrat, 'Adresse.nomcom' ),
			( empty( $lib_type_orient ) ? 'Non orienté' : $lib_type_orient ),
			@$contrat['Referent']['nom_complet'],
			Hash::get( $contrat, 'Structurereferente.lib_struc' ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] ),
			date_short( Hash::get( $contrat, 'Contratinsertion.dd_ci' ) ),
			$duree,
			date_short( Hash::get( $contrat, 'Contratinsertion.df_ci' ) ),
			$decision,
			Set::enum( Hash::get( $contrat, 'Contratinsertion.actions_prev' ), $action ),
			Hash::get( $contrat, 'Structurereferenteparcours.lib_struc' ),
			Hash::get( $contrat, 'Referentparcours.nom_complet' ),
		);

		if( Configure::read( 'Cg.departement' ) == 66 ) {
			$row = array_merge(
				$row,
				array( Hash::get( $contrat, 'Canton.canton' ) )
			);
		}

		if( Configure::read( 'Cg.departement' ) == 58 ) {
			$row = array_merge(
				$row,
				array(
					value( (array)Hash::get( $options, 'Personne.etat_dossier_orientation' ), Hash::get( $contrat, 'Personne.etat_dossier_orientation' ) )
				)
			);
		}
		else if( Configure::read( 'Cg.departement' ) == 93 ) {
			$row = array_merge(
				$row,
				array(
					// Expériences professionnelles significatives
					Hash::get( $contrat, 'Metierexerce.name' ),
					Hash::get( $contrat, 'Secteuracti.name' ),
					// Emploi trouvé (ROME v.3)
					Hash::get( $contrat, 'Familleemptrouv.name' ),
					Hash::get( $contrat, 'Domaineemptrouv.name' ),
					Hash::get( $contrat, 'Metieremptrouv.name' ),
					Hash::get( $contrat, 'Appellationemptrouv.name' ),
					// Votre contrat porte sur
					Hash::get( $contrat, 'Sujetcer93.name' ),
					Hash::get( $contrat, 'Cer93Sujetcer93.commentaireautre' ),
					Hash::get( $contrat, 'Soussujetcer93.name' ),
					Hash::get( $contrat, 'Cer93Sujetcer93.autresoussujet' ),
					Hash::get( $contrat, 'Valeurparsoussujetcer93.name' ),
					Hash::get( $contrat, 'Cer93Sujetcer93.autrevaleur' ),
				)
			);
		}

		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_engagement-'.date( 'Ymd-His' ).'.csv' );
?>