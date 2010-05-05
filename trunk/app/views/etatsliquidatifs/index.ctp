<?php
	$this->pageTitle = 'États liquidatifs APRE';

	echo $html->tag( 'h1', $this->pageTitle );

	if( $permissions->check( 'etatsliquidatifs', 'add' ) ) {
		echo $html->tag(
			'ul',
			$html->tag(
				'li',
				$html->addLink(
					'Ajouter un état liquidatif',
					array( 'controller' => 'etatsliquidatifs', 'action' => 'add' )
				)
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( empty( $etatsliquidatifs ) ) {
		echo $html->tag( 'p', 'Aucun état liquidatif pour l\'instant.', array( 'class' => 'notice' ) );
	}
	else {
        $pagination = $xpaginator->paginationBlock( 'Etatliquidatif', $this->passedArgs );
// 		$paginator->options( array( 'url' => $this->passedArgs ) );
// 		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
// 		$pagination = $html->tag( 'p', $paginator->counter( $params ) );
// 
// 		$pages = $paginator->first( '<<' );
// 		$pages .= $paginator->prev( '<' );
// 		$pages .= $paginator->numbers();
// 		$pages .= $paginator->next( '>' );
// 		$pages .= $paginator->last( '>>' );
// 
// 		$pagination .= $html->tag( 'p', $pages );

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
		$thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );
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
				$theme->button( 'edit', array( 'controller' => 'etatsliquidatifs', 'action' => 'edit', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Modifier', 'title' => 'Modifier l\'état liquidatif', 'enabled' => !$cloture ) ),
				$theme->button( 'selection', array( 'controller' => 'etatsliquidatifs', 'action' => 'selectionapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Sélection APREs', 'enabled' => !$cloture ) ),

                $theme->button( 'money', array( 'controller' => 'etatsliquidatifs', 'action' => 'versementapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Versements', 'enabled' => ( $isComplementaire && !$cloture && !empty( $apres_etatsliquidatifs ) ) ) ),

				$theme->button( 'validate', array( 'controller' => 'etatsliquidatifs', 'action' => 'validation', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'enabled' => ( !$cloture && !empty( $apres_etatsliquidatifs ) ) ) ),

				$theme->button( 'table', array( 'controller' => 'etatsliquidatifs', 'action' => 'hopeyra', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'HOPAYRA', 'enabled' => ( $cloture && !$isComplementaire ) ) ),
				$theme->button( 'pdf', array( 'controller' => 'etatsliquidatifs', 'action' => 'pdf', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'PDF', 'title' => 'Etat liquidatif', 'enabled' => $cloture ) ),
                $theme->button( 'table', array( 'controller' => 'etatsliquidatifs', 'action' => 'visualisationapres', Set::classicExtract( $etatliquidatif, 'Etatliquidatif.id' ) ), array( 'text' => 'Notifications', 'enabled' => $cloture ) )
			);
		}
		$tbody = $html->tag( 'tbody', $html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		///
		echo $pagination;
		echo $html->tag( 'table', $thead.$tbody );
		echo $pagination;
	}
?>