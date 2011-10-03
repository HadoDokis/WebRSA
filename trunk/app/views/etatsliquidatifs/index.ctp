<?php
	$this->pageTitle = 'États liquidatifs APRE';

	echo $xhtml->tag( 'h1', $this->pageTitle );

	if( $permissions->check( 'etatsliquidatifs', 'add' ) ) {
		echo $xhtml->tag(
			'ul',
			$xhtml->tag(
				'li',
				$xhtml->addLink(
					'Ajouter un état liquidatif',
					array( 'action' => 'add' )
				)
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( empty( $etatsliquidatifs ) ) {
		echo $xhtml->tag( 'p', 'Aucun état liquidatif pour l\'instant.', array( 'class' => 'notice' ) );
	}
	else {
		$pagination = $xpaginator->paginationBlock( 'Etatliquidatif', $this->passedArgs );
// 		$paginator->options( array( 'url' => $this->passedArgs ) );
// 		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
// 		$pagination = $xhtml->tag( 'p', $paginator->counter( $params ) );
// 
// 		$pages = $paginator->first( '<<' );
// 		$pages .= $paginator->prev( '<' );
// 		$pages .= $paginator->numbers();
// 		$pages .= $paginator->next( '>' );
// 		$pages .= $paginator->last( '>>' );
// 
// 		$pagination .= $xhtml->tag( 'p', $pages );

		//----------------------------------------------------------------------

		$headers = array(
			'Entité financière',
			'Opération',
			'Exercice budgétaire',
			'Nature analytique',
			'Cdr.',
			'Commentaire',
			'Date clôture',
		);

		///
		$thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );
		$thead = str_replace( '</tr>', '<th colspan="7">Action</th></tr>', $thead );



		/// Corps du tableau
		$rows = array();
		$isComplementaire = false;
		foreach( $etatsliquidatifs as $etatliquidatif ) {

			$statut = Set::classicExtract( $etatliquidatif, 'Etatliquidatif.typeapre' );
			if( $statut == 'complementaire' ){
				$isComplementaire = true;
			}
			else if( $statut == 'forfaitaire' ) {
				$isComplementaire = false;
			}


			$cloture = Set::classicExtract( $etatliquidatif, 'Etatliquidatif.datecloture' );
			$cloture = ( !empty( $cloture ) );
			$rows[] = array(
				Set::classicExtract( $etatliquidatif, 'Etatliquidatif.entitefi' ),
				Set::classicExtract( $etatliquidatif, 'Etatliquidatif.operation' ),
				Set::classicExtract( $etatliquidatif, 'Budgetapre.exercicebudgetai' ),
				Set::classicExtract( $etatliquidatif, 'Etatliquidatif.natureanalytique' ),
				Set::classicExtract( $etatliquidatif, 'Etatliquidatif.libellecdr' ),
				Set::classicExtract( $etatliquidatif, 'Etatliquidatif.commentaire' ),
				$locale->date( 'Date::short', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.datecloture' ) ),
				// FIXME: droits
				$theme->button( 'edit', array( 'action' => 'edit', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Modifier', 'title' => 'Modifier l\'état liquidatif', 'enabled' => !$cloture ) ),
				$theme->button( 'selection', array( 'action' => 'selectionapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Sélection APREs', 'enabled' => !$cloture ) ),

				$theme->button( 'money', array( 'action' => 'versementapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Versements', 'enabled' => ( $isComplementaire && !$cloture && !empty( $apres_etatsliquidatifs ) ) ) ),

				$theme->button( 'validate', array( 'action' => 'validation', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'enabled' => ( !$cloture && !empty( $apres_etatsliquidatifs ) ) ) ),

				$theme->button( 'table', array( 'action' => 'hopeyra', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'HOPEYRA', 'enabled' => ( $cloture && !$isComplementaire ) ) ),
				$theme->button( 'pdf', array( 'action' => 'pdf', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'PDF', 'title' => 'Etat liquidatif', 'enabled' => $cloture ) ),
				$theme->button( 'table', array( 'action' => 'visualisationapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Notifications', 'enabled' => $cloture ) )
			);
		}
		$tbody = $xhtml->tag( 'tbody', $xhtml->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		///
		echo $pagination;
		echo $xhtml->tag( 'table', $thead.$tbody, array( 'class' => 'nocssicons' ) );
		echo $pagination;
	}
?>