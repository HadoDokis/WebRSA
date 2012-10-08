<?php
	$this->pageTitle = 'Liste des cantons';

	echo $this->Xform->create( 'Canton' );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	$pagination = $this->Xpaginator->paginationBlock( 'Canton', $this->passedArgs );

	if( true || $this->Permissions->check( 'cantons', 'add' ) ) { // FIXME
		echo $this->Xhtml->tag( 'ul',
			$this->Xhtml->tag( 'li',
				$this->Xhtml->addLink( 'Ajouter un canton', array( 'action' => 'add' ) )
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( !empty( $cantons ) ) {
		$headers = array(
			$this->Xpaginator->sort( 'Canton', 'Canton.canton' ),
			$this->Xpaginator->sort( 'Zone géographique', 'Zonegeographique.libelle' ),
			$this->Xpaginator->sort( 'Type de voie', 'Canton.typevoie' ),
			$this->Xpaginator->sort( 'Nom de voie', 'Canton.nomvoie' ),
			$this->Xpaginator->sort( 'Localité', 'Canton.locaadr' ),
			$this->Xpaginator->sort( 'Code postal', 'Canton.codepos' ),
			$this->Xpaginator->sort( 'Code INSEE', 'Canton.numcomptt' )
		);
		$thead = $this->Xhtml->tag( 'thead', $this->Xhtml->tableHeaders( $headers ) );
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
				$this->Xhtml->editLink( 'Modifier le canton', array( 'action' => 'edit', Set::extract( $canton, 'Canton.id' ) ), true || $this->Permissions->check( 'cantons', 'edit' ) ), // FIXME
				$this->Xhtml->deleteLink( 'Supprimer le canton', array( 'action' => 'delete', Set::extract( $canton, 'Canton.id' ) ), true || $this->Permissions->check( 'cantons', 'delete' ) ), // FIXME
			);
		}
		$tbody = $this->Xhtml->tag( 'tbody', $this->Xhtml->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		echo $pagination;
		echo $this->Xhtml->tag( 'table', $thead.$tbody );
		echo $pagination;
	}
	else {
		echo $this->Xhtml->tag( 'p', 'Aucun canton n\'est renseigné pour l\'instant.', array( 'class' => 'notice' ) );
	}

	echo '<div class="submit">';
	echo $this->Xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
	echo '</div>';
	echo $this->Xform->end();
?>