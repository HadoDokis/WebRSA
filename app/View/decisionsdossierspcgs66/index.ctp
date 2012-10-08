<?php $this->pageTitle = 'Paramétrages des décisions de dossiers PCGs';?>
<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $form->create( 'DecisionsdossiersPCGs', array( 'url'=> Router::url( null, true ) ) );?>
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
						h( 'Compositions de foyer' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'composfoyerspcgs66', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				echo $xhtml->tableCells(
					array(
						h( 'Décision PCGs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'decisionspcgs66', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				
				echo $xhtml->tableCells(
					array(
						h( 'Tableau des questions' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'questionspcgs66', 'action' => 'index' )
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