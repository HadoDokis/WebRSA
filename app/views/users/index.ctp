<?php
	$this->pageTitle = 'Paramétrage des utilisateurs';
	$authUser = $session->read( 'Auth.User.id' );

	$paginationBlock = $xpaginator->paginationBlock(
		'User',
		Set::merge(
			$this->params['pass'],
			$this->params['named']
		)
	);
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
    <div>
        <h2>Table Utilisateur</h2>
        <?php echo $paginationBlock;?>
        <table>
        <thead>
            <tr>
                <th><?php echo $paginator->sort( 'Nom', 'User.nom' );?></th>
                <th><?php echo $paginator->sort( 'Prénom', 'User.prenom' );?></th>
                <th><?php echo $paginator->sort( 'Login', 'User.username' );?></th>
                <th><?php echo $paginator->sort( 'Date de naissance', 'User.date_naissance' );?></th>
                <th><?php echo $paginator->sort( 'N° téléphone', 'User.numtel' );?></th>
                <th><?php echo $paginator->sort( 'Date début habilitation', 'User.date_deb_hab' );?></th>
                <th><?php echo $paginator->sort( 'Date fin habilitation', 'User.date_fin_hab' );?></th>
                <th><?php echo $paginator->sort( 'Groupe d\'utilisateur', 'Group.name' );?></th>
                <th><?php echo $paginator->sort( 'Service instructeur', 'Serviceinstructeur.lib_service' );?></th>
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
                        );
                ?>
            <?php endforeach;?>
            </tbody>
        </table>
		<?php echo $paginationBlock;?>
	</div>
</div>
<div class="submit">
	<?php
		echo $xform->create( 'User' );
		echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		echo $xform->end();
	?>
</div>
<div class="clearer"><hr /></div>