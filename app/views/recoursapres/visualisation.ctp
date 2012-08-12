<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Visualisation des décisions des recours';?>

<h1>Décisions des recours</h1>

<?php
	if( isset( $recoursapres ) ) {
		$pagination = $xpaginator->paginationBlock( 'ApreComiteapre', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>

<?php require_once( 'filtre.ctp' );?>

<!-- Résultats -->

<?php if( isset( $recoursapres ) ):?>
	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $recoursapres ) && count( $recoursapres ) > 0 ):?>
		<?php echo $form->create( 'RecoursApre', array( 'url'=> Router::url( null, true ) ) );?>
	<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° demande APRE', 'Apre.numeroapre' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date demande APRE', 'Apre.datedemandeapre' );?></th>
					<th><?php echo $xpaginator->sort( 'Décision comité examen', 'ApreComiteapre.decisioncomite' );?></th>
					<th><?php echo $xpaginator->sort( 'Date décision comité', 'Comiteapre.datecomite' );?></th>
					<th>Demande de recours</th>
					<th>Date recours</th>
					<th>Observations</th>

					<th class="action">Notification Bénéficiaire</th>
					<th class="action">Notification Référent</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $recoursapres as $index => $recours ):?>
				<?php
					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $recours['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $recours['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $recours['Adresse']['codepos'] ).'</td>
								</tr>
							</tbody>
						</table>';
						$title = $recours['Dossier']['numdemrsa'];

					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $recours, 'Apre.numeroapre' ) ),
							h( Set::classicExtract( $recours, 'Personne.qual' ).' '.Set::classicExtract( $recours, 'Personne.nom' ).' '.Set::classicExtract( $recours, 'Personne.prenom' ) ),
							h( Set::classicExtract( $recours, 'Adresse.locaadr' ) ),
							h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'Apre.datedemandeapre' ) ) ),
							h( Set::enum( Set::classicExtract( $recours, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ) ),
							h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'Comiteapre.datecomite' ) ) ),
							h( Set::enum( Set::classicExtract( $recours, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] ) ),
							h( $locale->date( 'Date::short', Set::classicExtract( $recours, 'ApreComiteapre.daterecours' ) ) ),
							h( Set::classicExtract( $recours, 'ApreComiteapre.observationrecours' ) ),
							$xhtml->printLink(
								'Imprimer pour le bénéficiaire',
								array( 'controller' => 'recoursapres', 'action' => 'impression', Set::classicExtract( $recours, 'ApreComiteapre.apre_id' ), 'dest' => 'beneficiaire' ),
								$permissions->check( 'recoursapres', 'impression' )
							),
							$xhtml->printLink(
								'Imprimer pour le référent',
								array( 'controller' => 'recoursapres', 'action' => 'impression', Set::classicExtract( $recours, 'ApreComiteapre.apre_id' ), 'dest' => 'referent' ),
								$permissions->check( 'recoursapres', 'impression' )
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
					array( 'controller' => 'recoursapres', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
		<?php echo $form->end();?>

	<?php else:?>
		<p>Aucune demande de recours présente.</p>
	<?php endif?>
<?php endif?>