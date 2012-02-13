<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php require_once( 'filtre.ctp' );?>

<?php
	if( isset( $cohortecui ) ) {
		$pagination = $xpaginator->paginationBlock( 'Cui', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>
<?php if( !empty( $this->data ) ):?>
	<?php if( empty( $cohortecui ) ):?>
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
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date du contrat', 'Cui.datecontrat' );?></th>
					<th><?php echo $xpaginator->sort( 'Décision', 'Cui.decisioncui' );?></th>
					<th><?php echo $xpaginator->sort( 'Observations', 'Cui.observcui' );?></th>

					<th class="action">Action</th>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohortecui as $index => $contrat ):?>
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

						echo $xhtml->tableCells(
							array(
								h( $contrat['Dossier']['numdemrsa'] ),
								h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
								h( $contrat['Adresse']['locaadr'] ),
								h( date_short( $contrat['Cui']['datecontrat'] ) ),
								h( Set::enum( $contrat['Cui']['decisioncui'], $options['decisioncui'] ).' '.date_short( $contrat['Cui']['datevalidationcui'] ) ),
								h( $contrat['Cui']['observcui'] ),
								$xhtml->viewLink(
									'Voir le contrat',
									array( 'controller' => 'cuis', 'action' => 'index', $contrat['Cui']['personne_id'] ),
									$permissions->check( 'cuis', 'index' )
								),
								array( $innerTable, array( 'class' => 'innerTableCell' ) ),
							),
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
					array( 'controller' => 'cohortescui', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php endif;?>
<?php endif;?>