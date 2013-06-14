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
				'Nb de propositions de décisions',
				'État du dossier',
				'Motif de la personne',
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

		$datetransmission = '';
		if( $result['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
			$datetransmission = ' le '.date_short( Set::classicExtract( $result, 'Decisiondossierpcg66.datetransmissionop' ) );
		}

		$etatdosrsaValue = Set::classicExtract( $result, 'Situationdossierrsa.etatdosrsa' );
		$etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';
		
		//Liste des différents motifs de la personne
		$differentsStatuts = '';
		foreach( $result['Personnepcg66']['listemotifs'] as $key => $statut ) {
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
				h( Set::classicExtract( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Set::classicExtract( $result, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $result, 'Personne.nom' ).' '.Set::classicExtract( $result, 'Personne.prenom' ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $locale->date( 'Locale->date',  Set::classicExtract( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				Set::enum( Set::classicExtract( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission,
				$differentsStatuts,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		else {
			$row = array(
				h( Set::classicExtract( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Set::classicExtract( $result, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $result, 'Personne.nom' ).' '.Set::classicExtract( $result, 'Personne.prenom' ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $locale->date( 'Locale->date',  Set::classicExtract( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Set::classicExtract( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				h( $result['Personnepcg66']['nbtraitements'] ),
				$traitementspcgs66,
				Set::enum( Set::classicExtract( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( "{$this->request->params['controller']}_{$this->request->params['action']}_".date( 'Ymd-His' ).'.csv' );
?>