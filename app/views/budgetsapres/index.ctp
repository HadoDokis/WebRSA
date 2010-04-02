<?php
	$this->pageTitle = 'Budgets APRE';

	echo $html->tag( 'h1', $this->pageTitle );

	if( $permissions->check( 'budgetsapres', 'add' ) ) {
		echo $html->tag(
			'ul',
			$html->tag(
				'li',
				$html->addLink(
					'Ajouter un budget',
					array( 'controller' => 'budgetsapres', 'action' => 'add' )
				)
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( empty( $budgetsapres ) ) {
		echo $html->tag( 'p', 'Aucun budget pour l\'instant.', array( 'class' => 'notice' ) );
	}
	else {
		$paginator->options( array( 'url' => $this->passedArgs ) );
		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
		$pagination = $html->tag( 'p', $paginator->counter( $params ) );

		$pages = $paginator->first( '<<' );
		$pages .= $paginator->prev( '<' );
		$pages .= $paginator->numbers();
		$pages .= $paginator->next( '>' );
		$pages .= $paginator->last( '>>' );

		$pagination .= $html->tag( 'p', $pages );

		//----------------------------------------------------------------------

		$headers = array(
			'Exercice budgétaire',
			'Date de début d\'exécution',
			'Date de fin d\'exécution',
			'Attribution état',
			'Consommation budget',
			'Ratio'
		);

		///
		$thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );
		$thead = str_replace( '</tr>', '<th colspan="2">Action</th></tr>', $thead );

		/// Corps du tableau
		$rows = array();
		foreach( $budgetsapres as $budgetapre ) {
			$montantattretat = Set::classicExtract( $budgetapre, 'Budgetapre.montantattretat' );
			$montantattretat = ( empty( $montantattretat ) ? 0 : $montantattretat );

			$montanttotalapre = Set::extract( $budgetapre, '/Etatliquidatif/montanttotalapre' );
			$montanttotalapre = array_sum( $montanttotalapre);

			$rows[] = array(
				Set::classicExtract( $budgetapre, 'Budgetapre.exercicebudgetai' ),
				$locale->date( 'Date::short', Set::classicExtract( $budgetapre, 'Budgetapre.ddexecutionbudge' ) ),
				$locale->date( 'Date::short', Set::classicExtract( $budgetapre, 'Budgetapre.dfexecutionbudge' ) ),
				$locale->money( $montantattretat, 2 ),
				$locale->money( $montanttotalapre, 2 ), // FIXME -> $montantattretat < $montanttotalapre
				$locale->number( ( $montanttotalapre / $montantattretat ) * 100, 2 ).'&nbsp;%',
				// FIXME: droits
				$html->editLink( 'Éditer le budget', array( 'controller' => 'budgetsapres', 'action' => 'edit', Set::classicExtract( $budgetapre, 'Budgetapre.id' ) ) ),
				//$html->viewLink( 'Voir états liquidatifs', array( 'controller' => 'etatsliquidatifs', 'action' => 'index', 'budgetapre_id' => Set::classicExtract( $budgetapre, 'Budgetapre.id' ) ) ),
				$theme->button( 'view', array( 'controller' => 'etatsliquidatifs', 'action' => 'index', 'budgetapre_id' => Set::classicExtract( $budgetapre, 'Budgetapre.id' ) ), array( 'text' => 'Voir états liquidatifs' ) ),
			);
		}
		$tbody = $html->tag( 'tbody', $html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );

		///
		echo $pagination;
		echo $html->tag( 'table', $thead.$tbody );
		echo $pagination;
	}
?>