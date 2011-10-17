<?php
	$this->pageTitle = 'Versements pour les APREs complémentaires pour l\'état liquidatif';

	echo $xhtml->tag( 'h1', $this->pageTitle );

	///Fin pagination


	if( empty( $apres ) ) {
		echo $xhtml->tag( 'p', 'Aucune APRE à sélectionner.', array( 'class' => 'notice' ) );
	}
	else {
		/*$paginator->options( array( 'url' => $this->passedArgs ) );
		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
		$pagination = $xhtml->tag( 'p', $paginator->counter( $params ) );

		$pages = $paginator->first( '<<' );
		$pages .= $paginator->prev( '<' );
		$pages .= ' '.$paginator->numbers().' ';
		$pages .= $paginator->next( '>' );
		$pages .= $paginator->last( '>>' );

		$pagination .= $xhtml->tag( 'p', $pages );*/
		$pagination = $xpaginator->paginationBlock( 'Apre', $this->passedArgs );

		$headers = array(
			$xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' ),
			$xpaginator->sort( 'N° APRE', 'Apre.numeroapre' ),
			$xpaginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' ),
			$xpaginator->sort( 'Nom bénéficiaire', 'Personne.nom' ),
			$xpaginator->sort( 'Prénom bénéficiaire', 'Personne.prenom' ),
			$xpaginator->sort( 'Adresse', 'Adresse.locaadr' ),
			$xpaginator->sort( 'Montant attribué par le comité', 'Apre.montantaverser' ),
			'Nb paiement souhaité',
			'Nb paiement effectué',
//             'Montant à verser',
			'Montant à verser',
			'Montant déjà versé',
		);

		///
		$thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );

		echo $xform->create( 'ApreEtatliquidatif' );
		// FIXME
		//echo '<div>'.$xform->input( 'Etatliquidatif.id', array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).'</div>';

		/// Corps du tableau
		$rows = array();
		$ajaxes = array();
		foreach( $apres as $i => $apre ) {
			$params = array( 'id' => "apre_{$i}", 'class' => ( ( $i % 2 == 1 ) ? 'odd' : 'even' ) );
			$rows[] = $xhtml->tag( 'tr', $apreversement->cells( $i, $apre, $nbpaiementsouhait ), $params );

			/**
			*   Ajax
			**/
			$ajaxes[] = $ajax->observeField(
				"Apre{$i}Nbpaiementsouhait",
				array(
					'success' => "\ntry {
	var json = request.responseText.evalJSON(true);
	$( 'ApreEtatliquidatif{$i}Montantattribue' ).value = json.montantattribue;
}
catch(e) {
	alert( 'Erreur' );
}",
					'url' => Router::url(
						array(
							'action' => 'ajaxmontant',
							$this->params['pass'][0],
							Set::classicExtract( $apre, 'Apre.id' ),
							$i
						),
						true
					)
				)
			);
		}
		$tbody = $xhtml->tag( 'tbody', implode( '', $rows ) );

// debug($etatliquidatif);

		echo $pagination;
		echo $xhtml->tag( 'table', $thead.$tbody );
		echo $pagination;

		$buttons = array();
		$buttons[] = $xform->submit( 'Valider la liste', array( 'div' => false ) );
		$buttons[] = $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		echo $xhtml->tag( 'div', implode( '', $buttons ), array( 'class' => 'submit' ) );

		echo $xform->end();
		echo implode( '', $ajaxes );
	}
?>