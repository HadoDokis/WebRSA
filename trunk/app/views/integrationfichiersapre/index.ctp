<?php
	$this->pageTitle = 'Journal d\'intégration des fichiers CSV pour l\'APRE';
	echo $xhtml->tag( 'h1', $this->pageTitle );
?>

<?php if( !empty( $integrationfichiersapre ) ):?>
	<?php
		$pagination = $xpaginator->paginationBlock( 'Integrationfichierapre', $this->passedArgs );
	?>

	<?php echo $pagination;?>
	<table>
		<thead>
			<tr>
				<th><?php echo $xpaginator->sort( 'Date d\'intégration', 'Integrationfichierapre.date_integration' );?></th>
				<th><?php echo $xpaginator->sort( 'À traiter', 'Integrationfichierapre.nbr_atraiter' );?></th>
				<th><?php echo $xpaginator->sort( 'Traité', 'Integrationfichierapre.nbr_succes' );?></th>
				<th><?php echo $xpaginator->sort( 'En erreur', 'Integrationfichierapre.nbr_erreurs' );?></th>
				<th><?php echo $xpaginator->sort( 'Fichier', 'Integrationfichierapre.fichier_in' );?></th>
				<th class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach( $integrationfichiersapre as $integration ) {
					echo $xhtml->tableCells(
						array(
							$locale->date( 'Datetime::short', Set::classicExtract( $integration, 'Integrationfichierapre.date_integration' ) ),
							$locale->number( Set::classicExtract( $integration, 'Integrationfichierapre.nbr_atraiter' ) ),
							$locale->number( Set::classicExtract( $integration, 'Integrationfichierapre.nbr_succes' ) ),
							$locale->number( Set::classicExtract( $integration, 'Integrationfichierapre.nbr_erreurs' ) ),
							h( Set::classicExtract( $integration, 'Integrationfichierapre.fichier_in' ) ),
							$xhtml->link(
								'Télécharger rejet',
								array( 'controller' => 'integrationfichiersapre', 'action' => 'download', Set::classicExtract( $integration, 'Integrationfichierapre.id' ) )
							),
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
			?>
		</tbody>
	</table>
	<?php echo $pagination;?>
<?php else:?>
	<p class="notice">Aucune intégration de fichier CSV pour l'APRE n'a encore été effectuée.</p>
<?php endif;?>