<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow(
		array( // FIXME: traductions
			'Thématique',
			'N° de dossier',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'N° allocataire',
			'Adresse.locaadr',
			'Dossierep.created',
			'Proposition validée en COV le',
			'Structure référente',
		)
	);

	foreach( $themesChoose as $theme ){
		foreach( $dossiers[$theme] as $dossier ) {
// debug( $dossier );
			$row = array(
				__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ),
				$type2->format( $dossier, 'Dossierep.id', array() ),
				$type2->format( $dossier, 'Personne.qual', array() ),
				$type2->format( $dossier, 'Personne.nom', array() ),
				$type2->format( $dossier, 'Personne.prenom', array() ),
				$type2->format( $dossier, 'Personne.dtnai', array() ),
				$type2->format( $dossier, 'Dossier.matricule', array() ),
				$type2->format( $dossier, 'Adresse.locaadr', array() ),
				$type2->format( $dossier, 'StatutrdvTyperdv.motifpassageep', array() ),
				$type2->format( $dossier, 'Dossierep.created', array() ),
				$type2->format( $dossier, 'Structurereferente.lib_struc', array() )
			);
			$csv->addRow($row);
		}

		// __d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )
		/*echo $default2->index(
			$dossiers[$theme],
			array(
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
				'labelcohorte' => 'Enregistrer',
				'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) ),
				'trClass' => $trClass,
			)
		);*/
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'liste_des_dossiers_selectionnables_en_commission_ep_'.$commissionep_id.'_'.date( 'Ymd-Hhm' ).'.csv' );
?>