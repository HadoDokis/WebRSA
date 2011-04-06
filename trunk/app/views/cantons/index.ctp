<?php
	$this->pageTitle = 'Liste des cantons';

    echo $xform->create( 'Canton' );

	echo $xhtml->tag( 'h1', $this->pageTitle );

	///
	/*$paginator->options( array( 'url' => $this->passedArgs ) );
	$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );

	$pagination = array(
		$paginator->first( '<<' ),
		$paginator->prev( '<' ),
		$paginator->numbers(),
		$paginator->next( '>' ),
		$paginator->last( '>>' )
	);
	$pagination = $xhtml->tag( 'p', implode( ' ', $pagination ) );*/
	$pagination = $xpaginator->paginationBlock( 'Canton', $this->passedArgs );

	if( true || $permissions->check( 'cantons', 'add' ) ) { // FIXME
		echo $xhtml->tag( 'ul',
			$xhtml->tag( 'li',
				$xhtml->addLink( 'Ajouter un canton', array( 'action' => 'add' ) )
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( !empty( $cantons ) ) {
		$headers = array(
			$xpaginator->sort( 'Canton', 'Canton.canton' ),
			$xpaginator->sort( 'Zone géographique', 'Zonegeographique.libelle' ),
			$xpaginator->sort( 'Type de voie', 'Canton.typevoie' ),
			$xpaginator->sort( 'Nom de voie', 'Canton.nomvoie' ),
			$xpaginator->sort( 'Localité', 'Canton.locaadr' ),
			$xpaginator->sort( 'Code postal', 'Canton.codepos' ),
			$xpaginator->sort( 'Code INSEE', 'Canton.numcomptt' )
		);
		$thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );
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
				$xhtml->editLink( 'Modifier le canton', array( 'action' => 'edit', Set::extract( $canton, 'Canton.id' ) ), true || $permissions->check( 'cantons', 'edit' ) ), // FIXME
				$xhtml->deleteLink( 'Supprimer le canton', array( 'action' => 'delete', Set::extract( $canton, 'Canton.id' ) ), true || $permissions->check( 'cantons', 'delete' ) ), // FIXME
			);
		}
		$tbody = $xhtml->tag( 'tbody', $xhtml->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

// 		echo $xhtml->tag( 'p', $paginator->counter( $params ) );
        echo $pagination;
		echo $xhtml->tag( 'table', $thead.$tbody );
        echo $pagination;
	}
	else {
		echo $xhtml->tag( 'p', 'Aucun canton n\'est renseigné pour l\'instant.', array( 'class' => 'notice' ) );
	}

    echo '<div class="submit">';
    echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
    echo '</div>';
    echo $xform->end();
?>