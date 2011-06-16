<?php $this->pageTitle = 'Paramétrages des Equipes Pluridisciplinaires';?>
<h1>Paramétrage des EPs</h1>

<?php echo $form->create( 'NouvellesEPs', array( 'url'=> Router::url( null, true ) ) );?>
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
						h( 'Fonction des membres d\'une EP' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'fonctionsmembreseps', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);/*
				echo $xhtml->tableCells(
					array(
						h( 'Liste de membres pour une EP' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'membreseps', 'action' => 'index' ),
							( ( $compteurs['Fonctionmembreep'] > 0 ) )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Liste des EPs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'eps', 'action' => 'index' ),
							( ( $compteurs['Regroupementep'] > 0 ) )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);*/
				echo $xhtml->tableCells(
					array(
						h( __d( 'regroupementep', 'Regroupementep::index', true ) ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'regroupementseps', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				if( Configure::read( 'Cg.departement' ) ==  93 ) {
					echo $xhtml->tableCells(
						array(
							h( 'Motifs de demandes de réorientation' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'motifsreorientseps93', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}

				if( Configure::read( 'Cg.departement' ) ==  66 ) {
					echo $xhtml->tableCells(
						array(
							h( 'Composition des Équipes Pluridisciplinaires' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'compositionsregroupementseps', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
			?>
		</tbody>
	</table>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $form->end();?>