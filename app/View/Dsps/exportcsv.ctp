<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$row = array(
		'N° Dossier',
		'N° CAF',
		'Etat du droit',
		'Qualité',
		'Nom',
		'Prénom',
		'N° CAF',
		'Numéro de voie',
		'Type de voie',
		'Nom de voie',
		'Complément adresse 1',
		'Complément adresse 2',
		'Code postal',
		'Commune',
		'Code secteur d\'activité',
		'Code métier',
		'Secteur dernière activité dominante',
		'Dernière activité dominante',
		'Code secteur recherché',
		'Code métier recherché',
		'Secteur activité recherché',
		'Activité recherchée',
		__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
		__d( 'search_plugin', 'Referentparcours.nom_complet' ),
	);

	if( Configure::read( 'Cg.departement' ) == 93 ) {
		$row = array_merge(
			$row,
			array(
				'Difficultés sociales',
				'Domaine d\'accompagnement individuel',
				'Obstacles à la recherche d\'emploi'
			)
		);
	}

	if( Configure::read( 'Romev3.enabled' ) ) {
		foreach( $prefixes as $prefix ) {
			foreach( $suffixes as $suffix ) {
				$row[] = __d( 'dsps', "Dsp.{$prefix}{$suffix}romev3_id" );
			}
		}
	}

	$this->Csv->addRow( $row );

	foreach( $dsps as $dsp ) {
            $key = $dsp['Donnees']['libsecactdomi66_secteur_id'] . '_' . $dsp['Donnees']['libactdomi66_metier_id'];
            $key2 = $dsp['Donnees']['libsecactrech66_secteur_id'] . '_' . $dsp['Donnees']['libemploirech66_metier_id'];

            $row = array(
                Hash::get( $dsp, 'Dossier.numdemrsa' ),
                Hash::get( $dsp, 'Dossier.matricule' ),
				value( $etatdosrsa, Hash::get( $dsp, 'Situationdossierrsa.etatdosrsa' ) ),
				value( $qual, Hash::get( $dsp, 'Personne.qual' ) ),
				Hash::get( $dsp, 'Personne.nom' ),
				Hash::get( $dsp, 'Personne.prenom' ),
				Hash::get( $dsp, 'Dossier.matricule'  ),
				Hash::get( $dsp, 'Adresse.numvoie' ),
				Hash::get( $dsp, 'Adresse.libtypevoie' ),
				Hash::get( $dsp, 'Adresse.nomvoie' ),
				Hash::get( $dsp, 'Adresse.complideadr' ),
				Hash::get( $dsp, 'Adresse.compladr' ),
				Hash::get( $dsp, 'Adresse.codepos' ),
				Hash::get( $dsp, 'Adresse.nomcom' ),
                Set::enum( $dsp['Donnees']['libsecactdomi66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key],
                $dsp['Donnees']['libsecactdomi'],
                $dsp['Donnees']['libactdomi'],
                Set::enum( $dsp['Donnees']['libsecactrech66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key2],
                $dsp['Donnees']['libsecactrech'],
                $dsp['Donnees']['libemploirech'],
				Hash::get( $dsp, 'Structurereferenteparcours.lib_struc' ),
				Hash::get( $dsp, 'Referentparcours.nom_complet' ),
            );

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$links = array(
					'Detaildifsoc.difsoc',
					'Detailaccosocindi.nataccosocindi',
					'Detaildifdisp.difdisp',
				);

				foreach( $links as $link ) {
					list( $modelName, $fieldName ) = model_field( $link );

					$cell = array();
					$values = vfListeToArray( $dsp['Donnees'][$fieldName] );
					if( !empty( $values ) ) {
						foreach( $values as $value ) {
							$cell[] = value( $options[$modelName][$fieldName], $value );
						}
					}

					$row[] = ( !empty( $cell ) ? '- '.implode( "\n- ", $cell ) : '' );
				}
			}

			if( Configure::read( 'Romev3.enabled' ) ) {
				foreach( $prefixes as $prefix ) {
					foreach( $suffixes as $suffix ) {
						$modelName = Inflector::classify( "{$prefix}{$suffix}romev3" );
						$row[] = Hash::get( $dsp, "{$modelName}.name" );
					}
				}
			}

            $this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'dsps-'.date( 'Ymd-His' ).'.csv' );
?>