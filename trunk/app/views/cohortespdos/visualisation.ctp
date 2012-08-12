<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

<?php
	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}

	if( isset( $cohortepdo ) ) {
		$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>

<?php require_once( 'filtre.ctp' );?>

<!-- Résultats -->

<?php if( isset( $cohortepdo ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
		<?php echo $form->create( 'GestionPDO', array( 'url'=> Router::url( null, true ) ) );?>
	<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'N° CAF/MSA', 'Dossier.matricule' );?></th>
					<th><?php echo $xpaginator->sort( 'Ville', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de la demande RSA', 'Dossier.dtdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Gestionnaire', 'Propopdo.user_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Commentaire', 'Propopdo.commentairepdo' );?></th>

					<th class="action">Action</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohortepdo as $index => $pdo ):?>
				<?php
					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $pdo['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $pdo['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $pdo['Adresse']['codepos'] ).'</td>
								</tr>
							</tbody>
						</table>';
						$title = $pdo['Dossier']['numdemrsa'];

					echo $xhtml->tableCells(
						array(
							h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
							h( Set::extract( $pdo, 'Dossier.matricule' ) ),
							h( Set::extract( $pdo, 'Adresse.locaadr' ) ),
							h( date_short( Set::extract( $pdo, 'Dossier.dtdemrsa' ) ) ),
							h( Set::classicExtract( $gestionnaire, Set::classicExtract( $pdo, 'Propopdo.user_id' ) ) ),
							h( Set::classicExtract( $pdo, 'Propopdo.commentairepdo' ) ),
							$xhtml->viewLink(
								'Voir la PDO « '.$title.' »',
								array( 'controller' => 'propospdos', 'action' => 'index', $pdo['Personne']['id'] )
							),
							array( $innerTable, array( 'class' => 'innerTableCell' ) )
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
					array( 'onclick' => 'printit(); return false;' )
				);
			?></li>

			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'cohortespdos', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
		<?php echo $form->end();?>

	<?php else:?>
		<p>Aucune PDO dans la cohorte.</p>
	<?php endif?>
<?php endif?>