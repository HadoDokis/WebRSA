<?php $this->pageTitle = 'Paramétrage des Groupes d\'utilisateurs';?>
<?php echo $this->Xform->create( 'Group' );?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'groups', 'action' => 'add' ),
				$this->Permissions->check( 'groups', 'add' )
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
				<?php echo $this->Xhtml->tableCells(
					array(
						h( $group['Group']['name'] ),
						h( $group['Group']['parent_id'] ),
						$this->Xhtml->editLink(
							'Éditer le groupe',
							array( 'controller' => 'groups', 'action' => 'edit', $group['Group']['id'] ),
							$this->Permissions->check( 'groups', 'edit' )
						),
						$this->Xhtml->deleteLink(
							'Supprimer le groupe',
							array( 'controller' => 'groups', 'action' => 'delete', $group['Group']['id'] ),
							$this->Permissions->check( 'groups', 'delete' )
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
			echo $this->Xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $this->Xform->end();?>