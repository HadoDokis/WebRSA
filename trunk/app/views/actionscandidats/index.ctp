<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
	);
?>
<?php
	if( isset( $actionscandidats ) ) {
		$pagination = $xpaginator->paginationBlock( 'Actioncandidat', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>
<?php if( empty( $actionscandidats ) ):?>
	<p class="notice">Aucune action présente</p>
<?php endif;?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter une action',
				array( 'controller' => 'actionscandidats', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<?php if( !empty( $actionscandidats ) ):?>
	<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Intitulé de l\'action', 'Actioncandidat.name' );?></th>
					<th><?php echo $xpaginator->sort( 'Code de l\'action', 'Actioncandidat.codeaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Chargé d\'insertion', 'Chargeinsertion.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Secrétaire', 'Secretaire.nom_complet' );?></th>
					<th><?php echo $xpaginator->sort( 'Ville', 'Actioncandidat.lieuaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Canton', 'Actioncandidat.cantonaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Début de l\'action', 'Actioncandidat.ddaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Fin de l\'action', 'Actioncandidat.dfaction' );?></th>
					<th><?php echo $xpaginator->sort( 'Nombre de postes disponibles', 'Actioncandidat.nbpostedispo' );?></th>
					<th><?php echo $xpaginator->sort( 'Nombre d\'heures disponibles', 'Actioncandidat.nbheuredispo' );?></th>
					<th><?php echo $xpaginator->sort( 'Présence fiche de candidature', 'Actioncandidat.hasfichecandidature' );?></th>
					<th><?php echo $xpaginator->sort( 'Libellé du partenaire', 'Contactpartenaire.Partenaire.libstruc' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom du contact', 'Contactpartenaire.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Active', 'Actioncandidat.actif' );?></th>
					<th><?php echo $xpaginator->sort( 'Modèle de notification', 'Actioncandidat.modele_document' );?></th>
					<th><?php echo $xpaginator->sort( 'Nb de fichiers liés', 'Fichiermodule.nbFichiersLies' );?></th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $actionscandidats as $actioncandidat ){
// debug($actioncandidat);
						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.name' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.codeaction' ) ),
								h( Set::classicExtract( $actioncandidat, 'Chargeinsertion.nom' ).' '.Set::classicExtract( $actioncandidat, 'Chargeinsertion.prenom' ) ),
								h( Set::classicExtract( $actioncandidat, 'Secretaire.nom' ).' '.Set::classicExtract( $actioncandidat, 'Secretaire.prenom' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.lieuaction' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.cantonaction' ) ),
								h( date_short( Set::classicExtract( $actioncandidat, 'Actioncandidat.ddaction' ) ) ),
								h( date_short( Set::classicExtract( $actioncandidat, 'Actioncandidat.dfaction' ) ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.nbpostedispo' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.nbheuredispo' ) ),
								h( Set::enum( Set::classicExtract( $actioncandidat, 'Actioncandidat.hasfichecandidature' ), $options['Actioncandidat']['hasfichecandidature'] ) ),
								h( Set::classicExtract( $actioncandidat, 'Contactpartenaire.Partenaire.libstruc' ) ),
								h( Set::classicExtract( $actioncandidat, 'Contactpartenaire.nom' ).' '.Set::classicExtract( $actioncandidat, 'Contactpartenaire.prenom' ) ),
								h( Set::enum( Set::classicExtract( $actioncandidat, 'Actioncandidat.actif' ), $options['Actioncandidat']['actif'] ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.modele_document' ) ),
								h( Set::classicExtract( $actioncandidat, 'Fichiermodule.nb_fichiers_lies' ) ),

								$xhtml->editLink(
									'Editer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'edit',
									$actioncandidat['Actioncandidat']['id'] ),
									( $permissions->check( 'actionscandidats', 'edit' ) == 1 )
								),
								$xhtml->deleteLink(
									'Supprimer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'delete',
									$actioncandidat['Actioncandidat']['id'] ),
									( $permissions->check( 'actionscandidats', 'delete' ) == 1 )
								)
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					}

				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
	<?php  endif;?>



<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'actionscandidats_personnes',
			'action'     => 'indexparams'
		),
		array(
			'id' => 'Back'
		)
	);
?>
