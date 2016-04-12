<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	$this->pageTitle = 'Paramétrage des utilisateurs';
	$authUser = $this->Session->read( 'Auth.User.id' );
?>
<div>
	<h1><?php echo $this->pageTitle;?></h1>

		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'users', 'action' => 'add' ),
					$this->Permissions->check( 'users', 'add' )
				).' </li>';
			?>
		</ul>
<?php
	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</li></ul>';
?>

<?php echo $this->Xform->create( 'User', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
		<fieldset>
			<?php echo $this->Xform->input( 'User.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Utilisateur</legend>
			<?php
				echo $this->Default2->subform(
					array(
						'User.username' => array( 'label' => __d( 'user', 'User.username' ) ),
						'User.nom' => array( 'label' => __d( 'user', 'User.nom' ) ),
						'User.prenom' => array( 'label' => __d( 'user', 'User.prenom' ), 'type' => 'text' ),
						'Group.name' => array( 'label' => __d( 'user', 'Group.name' ), 'options' => $options['Groups'] ),
						'Serviceinstructeur.lib_service' => array( 'label' => __d( 'serviceinstructeur', 'Serviceinstructeur.lib_service' ), 'options' => $options['Serviceinstructeur'] ),
						'User.communautesr_id' => array( 'label' => 'Communauté (chef de projet de ville communautaire)', 'options' => $options['communautessrs'] ),
						'User.structurereferente_id' => array( 'label' => 'Structure référente (CPDV, secrétariat PDV)', 'options' => $options['structuresreferentes'] ),
						'User.referent_id' => array( 'label' => 'Référent (chargé d\'insertion)', 'options' => $options['referents'] ),
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

		<div class="submit noprint">
			<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
			<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
		</div>

<?php echo $this->Xform->end();?>

<?php  if( isset( $users ) ): ?>
	<?php if( empty( $users ) ):?>
		<?php
			$message = 'Aucune utilisateur ne correspond à votre recherche';
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
	<?php $pagination = $this->Xpaginator->paginationBlock( 'User', $this->passedArgs ); ?>
	<?php echo $pagination;?>
	<h2>Table Utilisateur</h2>
	<table id="searchResults" class="tooltips">
		<thead>
			<tr>
				<th><?php echo $this->Xpaginator->sort( 'Nom', 'User.nom' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Prénom', 'User.prenom' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Login', 'User.username' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Date de naissance', 'User.date_naissance' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'N° téléphone', 'User.numtel' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Date début habilitation', 'User.date_deb_hab' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Date fin habilitation', 'User.date_fin_hab' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Groupe d\'utilisateur', 'Group.name' );?></th>
				<th><?php echo $this->Xpaginator->sort( 'Service instructeur', 'Serviceinstructeur.lib_service' );?></th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $users as $user ):?>
				<?php echo $this->Xhtml->tableCells(
					array(
						h( $user['User']['nom'] ),
						h( $user['User']['prenom'] ),
						h( $user['User']['username'] ),
						h( date_short( $user['User']['date_naissance'] ) ),
						h( $user['User']['numtel'] ),
						h( date_short( $user['User']['date_deb_hab'] ) ),
						h( date_short( $user['User']['date_fin_hab'] ) ),
						h( $user['Group']['name'] ) ,
						h( $user['Serviceinstructeur']['lib_service'] ),
						$this->Xhtml->editLink(
							'Éditer l\'utilisateur',
							array( 'controller' => 'users', 'action' => 'edit', $user['User']['id'] ),
							$this->Permissions->check( 'users', 'edit' )
						),
						$this->Xhtml->deleteLink(
							'Supprimer l\'utilisateur',
							array( 'controller' => 'users', 'action' => 'delete', $user['User']['id'] ),
							$this->Permissions->check( 'users', 'delete' ) && !$user['User']['occurences'] && ( $user['User']['id'] != $authUser )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				); ?>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php echo $pagination;?>
<?php endif;?>
<?php endif;?>
</div>