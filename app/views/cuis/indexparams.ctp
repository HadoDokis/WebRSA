<?php $this->pageTitle = 'Paramétrages des CUIs';?>
<h1>Paramétrage des CUIs</h1>

<?php echo $form->create( 'Cuis', array( 'url'=> Router::url( null, true ) ) );?>
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
						h( 'Motifs de sortie' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'motifssortiecuis66', 'action' => 'index' )
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