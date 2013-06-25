<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

    $this->Csv->addRow(
        array(
            __d( 'dossier', 'Dossier.numdemrsa' ),
            'Allocataire',
            'Origine de la PDO',
            'Type de dossier',
            'Date de réception',
            'Gestionnaire',
            'Décision',
            'Nb de propositions de décisions',
            'État du dossier',
            'Motif(s) de la personne',
            'Statut(s) de la personne',
            'Nb de fichiers dans la corbeille'
        )
    );


	foreach( $results as $i => $result ) {
        $row = array(
           h( Hash::get( $result, 'Dossier.numdemrsa' ) ),
            h( Set::enum( Hash::get( $result, 'Personne.qual' ), $qual ).' '.Hash::get( $result, 'Personne.nom' ).' '.Hash::get( $result, 'Personne.prenom' ) ),
            h( Set::enum( Hash::get( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
            h( Set::enum( Hash::get( $result, 'Traitementpcg66.typetraitement' ), $options['Traitementpcg66']['typetraitement'] ) ),
            h( Hash::get( $result, 'Situationpdo.libelle' ) ),
            h( Set::enum( Hash::get( $result, 'Traitementpcg66.descriptionpdo_id' ), $descriptionpdo ) ),
            h( $this->Locale->date( 'Locale->date',  Hash::get( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
            h( date_short( Hash::get( $result, 'Traitementpcg66.daterevision' ) ) ),
            h( date_short( Hash::get( $result, 'Traitementpcg66.dateecheance' ) ) ),
            h( Set::enum( Hash::get( $result, 'Traitementpcg66.clos' ), $options['Traitementpcg66']['clos'] ) ),
            h( Set::enum( Hash::get( $result, 'Traitementpcg66.annule' ), $options['Traitementpcg66']['annule'] ) ),
            h( $result['Fichiermodule']['nb_fichiers_lies'] ),
        );
        $this->Csv->addRow( $row );
    }

    

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( "{$this->request->params['controller']}_{$this->request->params['action']}_".date( 'Ymd-His' ).'.csv' );
?>