<h1><?php echo $this->pageTitle = 'Paramétrages des tables liées au CER';?></h1>

<table class="aere">
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
					h( 'Métiers exercés' ),
					$this->Xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'metiersexerces', 'action' => 'index' ),
						$this->Permissions->check( 'metiersexerces', 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
			echo $this->Xhtml->tableCells(
				array(
					h( 'Natures de contrat' ),
					$this->Xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'naturescontrats', 'action' => 'index' ),
						$this->Permissions->check( 'naturescontrats', 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
			echo $this->Xhtml->tableCells(
				array(
					h( 'Secteurs d\'activité' ),
					$this->Xhtml->viewLink(
						'Voir la table',
						array( 'controller' => 'secteursactis', 'action' => 'index' ),
						$this->Permissions->check( 'secteursactis', 'index' )
					)
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
		?>
	</tbody>
</table>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>