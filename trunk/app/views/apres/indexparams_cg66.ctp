<h1><?php echo $this->pageTitle = 'Paramétrages des APREs';?></h1>

<table >
	<thead>
		<tr>
			<th>Nom de Table</th>
			<th colspan="2" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php

			echo $xhtml->tableCells(
				array(
					h( 'Liste des aides de l\'APRE' ),
					$xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'typesaidesapres66', 'action' => 'index' ),
						( ( $compteurs['Pieceaide66'] > 0 ) && ( $compteurs['Themeapre66'] > 0 ) )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
			echo $xhtml->tableCells(
				array(
					h( 'Liste des pièces administratives' ),
					$xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'piecesaides66', 'action' => 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
			echo $xhtml->tableCells(
				array(
					h( 'Liste des pièces comptables' ),
					$xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'piecescomptables66', 'action' => 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
			echo $xhtml->tableCells(
				array(
					h( 'Thèmes de la demande d\'aide APRE' ),
					$xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'themesapres66', 'action' => 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
		?>
	</tbody>
</table>

<?php
	echo $default->button(
		'back',
		array( 'controller' => 'parametrages', 'action' => 'index' ),
		array(
			'id' => 'Back'
		)
	);
?>