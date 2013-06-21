<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	if( $this->request->params['pass'][0] == 'searchDossier') {
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
	}
	else {
		$this->Csv->addRow(
			array(
				__d( 'dossier', 'Dossier.numdemrsa' ),
				'Allocataire',
				'Origine de la PDO',
				'Type de dossier',
				'Date de réception',
				'Gestionnaire',
				'Nb de propositions de décisions',
				'Nb de traitements PCGs',
				'Type de traitement',
				'État du dossier',
				'Nb de fichiers dans la corbeille'
			)
		);
	}

	foreach( $results as $i => $result ) {

        // Date de transmission du dossier et organismes auxquels cela a été transmis
		$datetransmission = '';
		if( $result['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
			$datetransmission = ' le '.date_short( Hash::get( $result, 'Decisiondossierpcg66.datetransmissionop' ) );
            $orgs = Hash::get( $result, 'Decisiondossierpcg66.listorgs' );
            $listorgs = ' à '.implode( ', ',  $orgs );
		}

		$etatdosrsaValue = Hash::get( $result, 'Situationdossierrsa.etatdosrsa' );
		$etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';
		
		//Liste des différents motifs de la personne
		$differentsMotifs = '';
		foreach( $result['Personnepcg66']['listemotifs'] as $key => $motif ) {
			if( !empty( $motif ) ) {
				$differentsMotifs .= ". ".$motif."\n";
			}
		}
        
        //Liste des différents statuts de la personne
		$differentsStatuts = '';
		foreach( $result['Personnepcg66']['listestatuts'] as $key => $statut ) {
			if( !empty( $statut ) ) {
				$differentsStatuts .= ". ".$statut."\n";
			}
		}
       	
		//Liste des différents traitements PCGs de la personne PCG
		$traitementspcgs66 = '';
		foreach( $result['Dossierpcg66']['listetraitements'] as $key => $traitement ) {
			if( !empty( $traitement ) ) {
				$traitementspcgs66 .= '. '.Set::enum( $traitement, $options['Traitementpcg66']['typetraitement'] )."\n";
			}
		}
	
		if( $this->request->params['pass'][0] == 'searchDossier' ) {
			$row = array(
				h( Hash::get( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Hash::get( $result, 'Personne.qual' ), $qual ).' '.Hash::get( $result, 'Personne.nom' ).' '.Hash::get( $result, 'Personne.prenom' ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $this->Locale->date( 'Locale->date',  Hash::get( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
                h( Hash::get( $result, 'Decisionpdo.libelle' ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				Set::enum( Hash::get( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission.$listorgs,
				$differentsMotifs,
                $differentsStatuts,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		else {
			$row = array(
				h( Hash::get( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Hash::get( $result, 'Personne.qual' ), $qual ).' '.Hash::get( $result, 'Personne.nom' ).' '.Hash::get( $result, 'Personne.prenom' ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $this->Locale->date( 'Locale->date',  Hash::get( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				h( $result['Personnepcg66']['nbtraitements'] ),
				$traitementspcgs66,
				Set::enum( Hash::get( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( "{$this->request->params['controller']}_{$this->request->params['action']}_".date( 'Ymd-His' ).'.csv' );
?>