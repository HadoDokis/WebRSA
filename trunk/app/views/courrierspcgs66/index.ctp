<?php $this->pageTitle = 'Paramétrages du module de courriers PCGs';?>
<h1><?php echo $this->pageTitle;?></h1>

<?php echo $form->create( 'Courrierspcgs66', array( 'url'=> Router::url( null, true ) ) );?>
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
						h( 'Type de courriers' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typescourrierspcgs66', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				echo $xhtml->tableCells(
					array(
						h( 'Pièces liées aux types de courriers' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'piecestypescourrierspcgs66', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

			?>
		</tbody>
	</table>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $form->end();?>