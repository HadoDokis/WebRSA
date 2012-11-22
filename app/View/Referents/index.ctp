<?php $this->pageTitle = 'Paramétrage des référents';?>
<?php echo $this->Xform->create( 'Referent' );?>
<div>
	<h1><?php echo 'Visualisation de la table référents ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'referents', 'action' => 'add' ),
				$this->Permissions->check( 'referents', 'add' )
			).' </li>';
		?>
	</ul>

	<?php if( empty( $referents ) ):?>
		<p class="notice">Aucun référent présent pour le moment.</p>

	<?php else:?>
	<div>
		<h2>Table Référents</h2>
		<table>
		<thead>
			<tr>
				<th>Civilité</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Fonction</th>
				<th>N° téléphone</th>
				<th>Email</th>
				<th>Structure référente liée</th>
				<th>Actif</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $referents as $referent ):?>
				<?php echo $this->Xhtml->tableCells(
					array(
						h( $qual[$referent['Referent']['qual']] ),
						h( $referent['Referent']['nom'] ),
						h( $referent['Referent']['prenom'] ),
						h( $referent['Referent']['fonction'] ),
						h( $referent['Referent']['numero_poste'] ),
						h( $referent['Referent']['email'] ),
						h( $sr[$referent['Referent']['structurereferente_id']] ),
						h( Set::enum( $referent['Referent']['actif'], $options['actif'] ) ),
						$this->Xhtml->editLink(
							'Éditer le référent',
							array( 'controller' => 'referents', 'action' => 'edit', $referent['Referent']['id'] ),
							$this->Permissions->check( 'referents', 'edit' )
						),
						$this->Xhtml->deleteLink(
							'Supprimer le référent',
							array( 'controller' => 'referents', 'action' => 'delete', $referent['Referent']['id'] ),
							$this->Permissions->check( 'referents', 'delete' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				); ?>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
<?php endif?>
</div>
	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $this->Xform->end();?>