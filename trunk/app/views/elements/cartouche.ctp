<?php if( $session->check( 'Auth.User' ) ):?>
	<div id="pageCartouche">
		<table>
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Groupe</th>
					<th>Service instructeur</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td> <?php echo $xhtml->link(
						$session->read('Auth.User.nom' ),
						array(
							'controller'=>'users',
							'action'=>'changepass'
						)
					) ;?> </td>
					<td> <?php echo $session->read( 'Auth.User.prenom' ) ;?> </td>
					<td> <?php echo $session->read( 'Auth.Group.name' ) ;?> </td>
					<td> <?php echo $session->read( 'Auth.Serviceinstructeur.lib_service' ) ;?> </td>
				</tr>
			</tbody>
		</table>
	</div>
<?php endif;?>