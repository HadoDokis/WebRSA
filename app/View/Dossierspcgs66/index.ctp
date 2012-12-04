<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) ); ?>

<div class="with_treemenu">

<h1><?php 
echo $this->pageTitle = 'Dossiers PCGs concernant le '.Set::classicExtract( $rolepers, Set::classicExtract( $personneDem, 'Prestation.rolepers' ) ).' : '.Set::classicExtract( $qual, Set::classicExtract( $personneDem, 'Personne.qual' ) ).' '.Set::classicExtract( $personneDem, 'Personne.nom' ).' '.Set::classicExtract( $personneDem, 'Personne.prenom' ); ?></h1>
		<?php if( $this->Permissions->check( 'dossierspcgs66', 'add' ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$this->Xhtml->addLink(
						'Ajouter un dossier',
						array( 'controller' => 'dossierspcgs66', 'action' => 'add', $foyer_id )
					).' </li>';
				?>
			</ul>
		<?php endif;?>
		<?php if( empty( $dossierspcgs66 ) ):?>
			<p class="notice">Ce foyer ne possède pas encore de dossier PCG.</p>
		<?php endif;?>

		<?php if( !empty( $dossierspcgs66 ) ):?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Type de PDO</th>
					<th>Date de réception de la PDO</th>
					<th>Gestionnaire du dossier</th>
					<th>Etat du dossier PDO</th>
<!-- 					<th>Dossier complet ?</th> -->
					<th>Décision</th>
					<th colspan="3" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $dossierspcgs66 as $dossierpcg66 ):?>
					<?php
// 					debug($dossierpcg66);
						$etat = null;
						if( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ) == 'transmisop' ){
// 							foreach( $dossierpcg66['Decisiondossierpcg66'] as $decision => $value  ){
// 							debug($dossierpcg66);
// 								if( $value['etatop'] == 'transmis' ){
									$etat = Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ). ' le '.date_short( Set::classicExtract( $dossierpcg66, 'Decisiondossierpcg66.0.datetransmissionop' ) );
// 									debug($dossierpcg66);
// 								}
// 								else{
// 									$etat = Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] );
// 								}
// 							}
						}
						else{
							$etat = Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] );
						}
						

					
						echo $this->Xhtml->tableCells(
							array(
								h( Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
								h( date_short( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.datereceptionpdo' ) ) ),
								h( Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
								h( $etat ),
// 								h( Set::enum( Set::classicExtract( $dossierpcg66, 'Dossierpcg66.iscomplet' ), $options['Dossierpcg66']['iscomplet'] ) ),
								h( Set::enum( Set::classicExtract( $dossierpcg66, 'Decisiondossierpcg66.decisionpdo_id' ), $decisionpdo ) ),
								$this->Xhtml->viewLink(
									'Voir le dossier',
									array( 'controller' => 'dossierspcgs66', 'action' => 'view', $dossierpcg66['Dossierpcg66']['id']),
									$this->Permissions->check( 'dossierspcgs66', 'view' )
								),
								$this->Xhtml->editLink(
									'Éditer le dossier',
									array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66['Dossierpcg66']['id'] ),
									$this->Permissions->check( 'dossierspcgs66', 'edit' )
								),
								$this->Xhtml->deleteLink(
									'Supprimer le dossier',
									array( 'controller' => 'dossierspcgs66', 'action' => 'delete', $dossierpcg66['Dossierpcg66']['id'] ),
									$this->Permissions->check( 'dossierspcgs66', 'delete' )
								)
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php  endif;?>
</div><div class="clearer"></div>