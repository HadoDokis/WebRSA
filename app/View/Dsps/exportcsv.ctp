<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'N° CAF',  'Nom/Prénom allocataire', 'Code secteur d\'activité', 'Code métier', 'Secteur dernière activité dominante', 'Dernière activité dominante', 'Code secteur recherché', 'Code métier recherché', 'Secteur activité recherché', 'Activité recherchée' ) );

	foreach( $dsps as $dsp ) {
            $key = $dsp['Donnees']['libsecactdomi66_secteur_id'] . '_' . $dsp['Donnees']['libactdomi66_metier_id'];
            $key2 = $dsp['Donnees']['libsecactrech66_secteur_id'] . '_' . $dsp['Donnees']['libemploirech66_metier_id'];
							
            $row = array(
                Set::classicExtract( $dsp, 'Dossier.numdemrsa' ),
                Set::classicExtract( $dsp, 'Dossier.matricule' ),
                Set::classicExtract( $dsp, 'Personne.qual' ).' '.Set::classicExtract( $dsp, 'Personne.nom' ).' '.Set::classicExtract( $dsp, 'Personne.prenom' ),
                Set::enum( $dsp['Donnees']['libsecactdomi66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key],
                $dsp['Donnees']['libsecactdomi'],
                $dsp['Donnees']['libactdomi'],
                Set::enum( $dsp['Donnees']['libsecactrech66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key2],
                $dsp['Donnees']['libsecactrech'],
                $dsp['Donnees']['libemploirech']
            );
            $this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'dsps-'.date( 'Ymd-Hhm' ).'.csv' );
?>