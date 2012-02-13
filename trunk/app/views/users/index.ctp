<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$this->pageTitle = 'Paramétrage des utilisateurs';
	$authUser = $session->read( 'Auth.User.id' );
?>
<div>
	<h1><?php echo $this->pageTitle;?></h1>

		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'users', 'action' => 'add' )
				).' </li>';
			?>
		</ul>
<?php
	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</li></ul>';
?>

<?php echo $xform->create( 'User', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
		<fieldset>
			<?php echo $xform->input( 'User.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Utilisateur</legend>
			<?php
				echo $default2->subform(
					array(
						'User.username' => array( 'label' => __d( 'user', 'User.username', true ) ),
						'User.nom' => array( 'label' => __d( 'user', 'User.nom', true ) ),
						'User.prenom' => array( 'label' => __d( 'user', 'User.prenom', true ), 'type' => 'text' ),
						'Group.name' => array( 'label' => __d( 'user', 'Group.name', true ), 'options' => $options['Groups'] ),
						'Serviceinstructeur.lib_service' => array( 'label' => __d( 'serviceinstructeur', 'Serviceinstructeur.lib_service', true ), 'options' => $options['Serviceinstructeur'] )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

		<div class="submit noprint">
			<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
			<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
		</div>

<?php echo $xform->end();?>

<?php  if( isset( $users ) ): ?>
	<?php if( empty( $users ) ):?>
		<?php
			$message = 'Aucune utilisateur ne correspond à votre recherche';
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
	<?php $pagination = $xpaginator->paginationBlock( 'User', $this->passedArgs ); ?>
	<?php echo $pagination;?>
	<h2>Table Utilisateur</h2>
	<table id="searchResults" class="tooltips">
		<thead>
			<tr>
				<th><?php echo $xpaginator->sort( 'Nom', 'User.nom' );?></th>
				<th><?php echo $xpaginator->sort( 'Prénom', 'User.prenom' );?></th>
				<th><?php echo $xpaginator->sort( 'Login', 'User.username' );?></th>
				<th><?php echo $xpaginator->sort( 'Date de naissance', 'User.date_naissance' );?></th>
				<th><?php echo $xpaginator->sort( 'N° téléphone', 'User.numtel' );?></th>
				<th><?php echo $xpaginator->sort( 'Date début habilitation', 'User.date_deb_hab' );?></th>
				<th><?php echo $xpaginator->sort( 'Date fin habilitation', 'User.date_fin_hab' );?></th>
				<th><?php echo $xpaginator->sort( 'Groupe d\'utilisateur', 'Group.name' );?></th>
				<th><?php echo $xpaginator->sort( 'Service instructeur', 'Serviceinstructeur.lib_service' );?></th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $users as $user ):?>
				<?php echo $xhtml->tableCells(
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
						$xhtml->editLink(
							'Éditer l\'utilisateur',
							array( 'controller' => 'users', 'action' => 'edit', $user['User']['id'] )
						),
						$xhtml->deleteLink(
							'Supprimer l\'utilisateur',
							array( 'controller' => 'users', 'action' => 'delete', $user['User']['id'] ), $permissions->check( 'users', 'delete' ) && ( $user['User']['id'] != $authUser )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				); ?>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php echo $pagination;?>
<?php
	echo $default->button(
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
<?php endif;?>
<?php endif;?>
</div>