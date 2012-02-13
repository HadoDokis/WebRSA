<?php $this->pageTitle = 'Paramétrage des Groupes d\'utilisateurs';?>
<?php echo $xform->create( 'Group' );?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'groups', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<div>
		<h2>Table Groupes d'utilisateurs</h2>
		<table>
		<thead>
			<tr>
				<th>Nom du groupe</th>
				<th>Parent</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $groups as $group ):?>
				<?php echo $xhtml->tableCells(
					array(
						h( $group['Group']['name'] ),
						h( $group['Group']['parent_id'] ),
						$xhtml->editLink(
							'Éditer le groupe',
							array( 'controller' => 'groups', 'action' => 'edit', $group['Group']['id'] )
						),
						$xhtml->deleteLink(
							'Supprimer le groupe',
							array( 'controller' => 'groups', 'action' => 'delete', $group['Group']['id'] )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				); ?>
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