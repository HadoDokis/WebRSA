<h1><?php

	if( Configure::read( 'Cg.departement' ) != 66 ) {
		$pageTitle = 'Contrats validés';
	}
	else{
		$pageTitle = 'Décisions prises';
	}

	echo $this->pageTitle = $pageTitle;
	?>
</h1><?php require_once( 'filtre.ctp' );?>

<?php
	if( isset( $cohorteci ) ) {
		$pagination = $xpaginator->paginationBlock( 'Contratinsertion', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>
<?php if( !empty( $this->data ) ):?>
	<?php if( empty( $cohorteci ) ):?>
		<?php
			switch( $this->action ) {
				case 'valides':
					$message = 'Aucun contrat ne correspond à vos critères.';
					break;
				default:
					$message = 'Aucun contrat de validé n\'a été trouvé.';
			}
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
		<?php echo $pagination;?>
		<table class="tooltips default2">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de début contrat', 'Contratinsertion.dd_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de fin contrat', 'Contratinsertion.df_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Décision', 'Contratinsertion.decision_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Observations', 'Contratinsertion.observ_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Forme du contrat', 'Contratinsertion.forme_ci' );?></th>

					<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
						<th><?php echo $xpaginator->sort( 'Position du contrat', 'Contratinsertion.positioncer' );?></th>
						<th colspan="5" class="action">Action</th>
					<?php else:?>
						<th class="action">Action</th>
					<?php endif;?>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohorteci as $index => $contrat ):?>
						<?php
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $contrat['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Numéro CAF</th>
									<td>'.h( $contrat['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $contrat['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $contrat['Adresse']['codepos'] ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $contrat['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.h( $rolepers[$contrat['Prestation']['rolepers']] ).'</td>
								</tr>	
								<tr>
									<th>État du dossier</th>
									<td>'.h( $etatdosrsa[$contrat['Situationdossierrsa']['etatdosrsa']] ).'</td>
								</tr>															
							</tbody>
						</table>';
						$title = $contrat['Dossier']['numdemrsa'];

						$array1 = array(
							h( $contrat['Dossier']['numdemrsa'] ),
							h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
							h( $contrat['Adresse']['locaadr'] ),
							h( date_short( $contrat['Contratinsertion']['dd_ci'] ) ),
							h( date_short( $contrat['Contratinsertion']['df_ci'] ) ),
							h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.date_short( $contrat['Contratinsertion']['datedecision'] ) ),
							h( $contrat['Contratinsertion']['observ_ci'] ),
							h( Set::classicExtract( $forme_ci, $contrat['Contratinsertion']['forme_ci'] ) ),
						);
						
						$array2 = array();
						if( Configure::read( 'Cg.departement' ) == 66 ){
							$array2 = array(
								h( Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ), $numcontrat['positioncer'] ) ),
								$default2->button(
									'view',
									array( 'controller' => 'contratsinsertion', 'action' => 'view',
									$contrat['Contratinsertion']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'contratsinsertion', 'view' ) == 1
										)
									)
								),
								$default2->button(
									'ficheliaisoncer',
									array( 'controller' => 'contratsinsertion', 'action' => 'ficheliaisoncer',
									$contrat['Contratinsertion']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'contratsinsertion', 'ficheliaisoncer' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'fincontrat' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'annule' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.decision_ci' ) == 'N' )
										)
									)
								),
								$default2->button(
									'notifbenef',
									array( 'controller' => 'contratsinsertion', 'action' => 'notifbenef',
									$contrat['Contratinsertion']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'contratsinsertion', 'notifbenef' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'fincontrat' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'annule' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.decision_ci' ) != 'E' )
										)
									)
								),
								$default2->button(
									'notifop',
									array( 'controller' => 'contratsinsertion', 'action' => 'notificationsop',
									$contrat['Contratinsertion']['id'] ),
									array(
										'enabled' => (
											( $permissions->check( 'contratsinsertion', 'notificationsop' ) == 1 )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'fincontrat' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'annule' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.decision_ci' ) == 'V' )
										)
									)
								),
								$default2->button(
									'print',
									array( 'controller' => 'gedooos', 'action' => 'contratinsertion',
									$contrat['Contratinsertion']['id'] ),
									array(
										'enabled' => (
											( $permissions->check( 'gedooos', 'contratinsertion' ) == 1 )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'annule' )
											&& ( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ) != 'fincontrat' )
										)
									)
								),
								array( $innerTable, array( 'class' => 'innerTableCell' ) ),
							);
						}
						else{
							$array2 = array(
								$xhtml->viewLink(
									'Voir le contrat',
									array( 'controller' => 'contratsinsertion', 'action' => 'view', $contrat['Contratinsertion']['id'] ),
									$permissions->check( 'contratsinsertion', 'view' )
								),
								array( $innerTable, array( 'class' => 'innerTableCell' ) ),
							);
						}
						
						
						echo $xhtml->tableCells(
							Set::merge( $array1, $array2 ),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<ul class="actionMenu">
			<li><?php
				echo $xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'cohortesci', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php endif;?>
<?php endif;?>