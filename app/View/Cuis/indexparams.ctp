<?php $this->pageTitle = 'Paramétrages des CUIs';?>
<h1>Paramétrage des CUIs</h1>

<?php echo $this->Form->create( 'Cuis', array( 'url'=> Router::url( null, true ) ) );?>
	<table >
		<thead>
			<tr>
				<th>Nom de Table</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				echo $this->Xhtml->tableCells(
					array(
						h( 'Motifs de sortie' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'motifssortiecuis66', 'action' => 'index' ),
							$this->Permissions->check( 'motifssortiecuis66', 'index' )
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
			echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $this->Form->end();?>