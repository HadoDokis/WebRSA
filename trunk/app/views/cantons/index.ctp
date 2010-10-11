<?php
	$this->pageTitle = 'Liste des cantons';

    echo $xform->create( 'Canton' );

	echo $html->tag( 'h1', $this->pageTitle );

	///
	$paginator->options( array( 'url' => $this->passedArgs ) );
	$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );

	$paginationLinks = array(
		$paginator->first( '<<' ),
		$paginator->prev( '<' ),
		$paginator->numbers(),
		$paginator->next( '>' ),
		$paginator->last( '>>' )
	);
	$paginationLinks = $html->tag( 'p', implode( ' ', $paginationLinks ) );

	if( true || $permissions->check( 'cantons', 'add' ) ) { // FIXME
		echo $html->tag( 'ul',
			$html->tag( 'li',
				$html->addLink( 'Ajouter un canton', array( 'action' => 'add' ) )
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( !empty( $cantons ) ) {
		$headers = array(
			$paginator->sort( 'Canton', 'Canton.canton' ),
			$paginator->sort( 'Zone géographique', 'Zonegeographique.libelle' ),
			$paginator->sort( 'Type de voie', 'Canton.typevoie' ),
			$paginator->sort( 'Nom de voie', 'Canton.nomvoie' ),
			$paginator->sort( 'Localité', 'Canton.locaadr' ),
			$paginator->sort( 'Code postal', 'Canton.codepos' ),
			$paginator->sort( 'Code INSEE', 'Canton.numcomptt' )
		);
		$thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );
		$thead = str_replace( '</th></tr>', '</th><th colspan="2">Actions</th></tr>', $thead );

		$rows = array();
		foreach( $cantons as $canton ) {
			$rows[] = array(
				h( Set::extract( $canton, 'Canton.canton' ) ),
				h( Set::extract( $canton, 'Zonegeographique.libelle' ) ),
				h( isset( $typevoie[Set::extract( $canton, 'Canton.typevoie' )] ) ? $typevoie[Set::extract( $canton, 'Canton.typevoie' )] : Set::extract( $canton, 'Canton.typevoie' ) ),
				h( Set::extract( $canton, 'Canton.nomvoie' ) ),
				h( Set::extract( $canton, 'Canton.locaadr' ) ),
				h( Set::extract( $canton, 'Canton.codepos' ) ),
				h( Set::extract( $canton, 'Canton.numcomptt' ) ),
				$html->editLink( 'Modifier le canton', array( 'action' => 'edit', Set::extract( $canton, 'Canton.id' ) ), true || $permissions->check( 'cantons', 'edit' ) ), // FIXME
				$html->deleteLink( 'Supprimer le canton', array( 'action' => 'delete', Set::extract( $canton, 'Canton.id' ) ), true || $permissions->check( 'cantons', 'delete' ) ), // FIXME
			);
		}
		$tbody = $html->tag( 'tbody', $html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		echo $html->tag( 'p', $paginator->counter( $params ) );
        echo $paginationLinks;
		echo $html->tag( 'table', $thead.$tbody );
        echo $paginationLinks;
	}
	else {
		echo $html->tag( 'p', 'Aucun canton n\'est renseigné pour l\'instant.', array( 'class' => 'notice' ) );
	}

    echo '<div class="submit">';
    echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
    echo '</div>';
    echo $xform->end();
?>