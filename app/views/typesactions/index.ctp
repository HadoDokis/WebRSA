<?php $this->pageTitle = 'Paramétrage des types d\'actions d\'insertion';?>
<?php echo $xform->create( 'Typeaction' );?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'typesactions', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<div>
		<h2>Table Type d'actions d'insertion</h2>
		<table>
		<thead>
			<tr>
				<th>Libellé de l'action</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $typesactions as $typeaction ):?>
				<?php echo $xhtml->tableCells(
							array(
								h( $typeaction['Typeaction']['libelle'] ),
								$xhtml->editLink(
									'Éditer le type d\'action',
									array( 'controller' => 'typesactions', 'action' => 'edit', $typeaction['Typeaction']['id'] )
								),
								$xhtml->deleteLink(
									'Supprimer le type d\'action',
									array( 'controller' => 'typesactions', 'action' => 'delete', $typeaction['Typeaction']['id'] )
								)
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
				?>
			<?php endforeach;?>
			</tbody>
		</table>
</div>
</div>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>