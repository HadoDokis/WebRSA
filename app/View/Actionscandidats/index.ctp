<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}" )
	);
?>
<?php
	if( isset( $actionscandidats ) ) {
		$pagination = $this->Xpaginator->paginationBlock( 'Actioncandidat', $this->passedArgs );
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
			echo '<li>'.$this->Xhtml->addLink(
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
					<th><?php echo $this->Xpaginator->sort( 'Intitulé de l\'action', 'Actioncandidat.name' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Code de l\'action', 'Actioncandidat.codeaction' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Chargé d\'insertion', 'Chargeinsertion.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Secrétaire', 'Secretaire.nom_complet' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Ville', 'Actioncandidat.lieuaction' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Canton', 'Actioncandidat.cantonaction' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Début de l\'action', 'Actioncandidat.ddaction' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Fin de l\'action', 'Actioncandidat.dfaction' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Correspondant de l\'action', 'Actioncandidat.referent_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nombre de postes disponibles', 'Actioncandidat.nbpostedispo' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nombre d\'heures disponibles', 'Actioncandidat.nbheuredispo' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Présence fiche de candidature', 'Actioncandidat.hasfichecandidature' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Libellé du partenaire', 'Partenaire.libstruc' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nom du contact', 'Contactpartenaire.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Active', 'Actioncandidat.actif' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Modèle de notification', 'Actioncandidat.modele_document' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nb de fichiers liés', 'Fichiermodule.nbFichiersLies' );?></th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $actionscandidats as $actioncandidat ){
// debug($actioncandidat);
						echo $this->Xhtml->tableCells(
							array(
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.name' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.codeaction' ) ),
								h( Set::classicExtract( $actioncandidat, 'Chargeinsertion.nom' ).' '.Set::classicExtract( $actioncandidat, 'Chargeinsertion.prenom' ) ),
								h( Set::classicExtract( $actioncandidat, 'Secretaire.nom' ).' '.Set::classicExtract( $actioncandidat, 'Secretaire.prenom' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.lieuaction' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.cantonaction' ) ),
								h( date_short( Set::classicExtract( $actioncandidat, 'Actioncandidat.ddaction' ) ) ),
								h( date_short( Set::classicExtract( $actioncandidat, 'Actioncandidat.dfaction' ) ) ),
								h( Set::classicExtract( $actioncandidat, 'Referent.nom_complet' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.nbpostedispo' ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.nbheuredispo' ) ),
								h( Set::enum( Set::classicExtract( $actioncandidat, 'Actioncandidat.hasfichecandidature' ), $options['Actioncandidat']['hasfichecandidature'] ) ),
								h( Set::classicExtract( $actioncandidat, 'Partenaire.libstruc' ) ),
								h( Set::classicExtract( $actioncandidat, 'Contactpartenaire.nom' ).' '.Set::classicExtract( $actioncandidat, 'Contactpartenaire.prenom' ) ),
								h( Set::enum( Set::classicExtract( $actioncandidat, 'Actioncandidat.actif' ), $options['Actioncandidat']['actif'] ) ),
								h( Set::classicExtract( $actioncandidat, 'Actioncandidat.modele_document' ) ),
								h( Set::classicExtract( $actioncandidat, 'Fichiermodule.nb_fichiers_lies' ) ),

								$this->Xhtml->editLink(
									'Editer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'edit',
									$actioncandidat['Actioncandidat']['id'] ),
									( $this->Permissions->check( 'actionscandidats', 'edit' ) == 1 )
								),
								$this->Xhtml->deleteLink(
									'Supprimer l\'action',
									array( 'controller' => 'actionscandidats', 'action' => 'delete',
									$actioncandidat['Actioncandidat']['id'] ),
									( $this->Permissions->check( 'actionscandidats', 'delete' ) == 1 &&  ( !$actioncandidat['Actioncandidat']['occurences']  ) )
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
	echo $this->Default->button(
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
