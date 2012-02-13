<?php $this->pageTitle = 'Paramétrages des DSPs';?>
<h1>Paramétrage des DSPs</h1>

<?php echo $form->create( 'NouvellesDsps', array( 'url'=> Router::url( null, true ) ) );?>
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
						h( 'Codes ROME pour les secteurs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'codesromesecteursdsps66', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Codes ROME pour les métiers' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'codesromemetiersdsps66', 'action' => 'index' )
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