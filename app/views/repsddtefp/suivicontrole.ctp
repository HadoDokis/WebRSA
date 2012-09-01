<?php
	$this->pageTitle = 'APRE: Suivi et contrôle de l\'enveloppe';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsOnValue( 'RepddtefpMoisMonth', [ 'RepddtefpQuinzaine' ], '', true );
	})
</script>
<!--/************************************************************************/ -->
<?php
	$pagination = $xpaginator->paginationBlock( 'Etatliquidatif', $this->passedArgs );

	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'ReportingApre' ).toggle(); return false;" )
		).'</li></ul>';
	}

?>
<!-- /************************************************************************/ -->

<?php
	echo $form->create( 'ReportingApre', array( 'url' => Router::url( null, true ), 'id' => 'ReportingApre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );

	echo $form->input( 'Repddtefp.annee', array( 'label' => 'Année', 'type' => 'select', 'options' => array_range( date( 'Y' ), 2008 ), 'empty' => true ) );
	echo $form->input( 'Repddtefp.mois', array( 'label' => 'Mois', 'type' => 'date', 'dateFormat' => 'M', 'empty' => true ) );
	echo $form->input( 'Repddtefp.quinzaine', array( 'label' => 'Quinzaine', 'type' => 'select', 'options' => $quinzaine, 'empty' => true   ) );
	echo $form->input( 'Repddtefp.statutapre', array( 'label' => 'Statut de l\'APRE', 'type' => 'select', 'options' => $options['statutapre'], 'empty' => true   ) );

	echo $form->input( 'Repddtefp.numcomptt', array( 'label' => __d( 'apre', 'Repddtefp.numcomptt', true ), 'type' => 'select', 'options' => $mesCodesInsee,  'empty' => true ) );

	echo $form->submit( 'Calculer' );
	echo $form->end();
?>

<?php if( !empty( $this->data ) ):?>
	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $apres ) && count( $apres ) > 0  ):?>
		<?php echo $pagination;?>
		<?php
			$annee = Set::classicExtract( $this->data, 'Repddtefp.annee' );
			echo '<h2>Données pour l\'année : '.$annee.'</h2>';
			$mois = Set::classicExtract( $this->data, 'Repddtefp.mois.month' );
			if( !empty( $mois ) ) {
				echo '<h2>Données pour le mois : '.$mois.'</h2>';
			}
			$quinzaine = Set::classicExtract( $this->data, 'Repddtefp.quinzaine' );
			if( !empty( $quinzaine ) ) {
				echo '<h2>Données pour la quinzaine : '.$quinzaine.'</h2>';
			}
		?>
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Confondus</th>
						<th>Complémentaires</th>
						<th>Forfaitaires</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Nombre d'APREs</th>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'A.nbrapres' );?></td>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'C.nbrapres' );?></td>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'F.nbrapres' );?></td>
					</tr>
					<tr>
						<th>Nombre de bénéficiaires</th>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'A.nbrpersonnes' );?></td>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'C.nbrpersonnes' );?></td>
						<td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'F.nbrpersonnes' );?></td>
					</tr>
					<tr>
						<th>Consommation de l'enveloppe</th>
						<td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'A.montantconsomme' ) );?></td>
						<td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'C.montantconsomme' ) );?></td>
						<td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'F.montantconsomme' ) );?></td>
					</tr>
				</tbody>
			</table>
		<table id="searchResults" >
			<thead>
				<tr>
					<th>Liste Bénéficiaire</th>
					<th>Sexe</th>
					<th>Age</th>
					<th>Domiciliation</th>
					<th>Montants des aides</th>
					<th>Nature des aides</th>
					<th>Nature de la reprise</th>
					<th>Secteur professionnel</th>
					<th>Statut de l'APRE</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$even = true;
				$montantTotal = 0;
			?>
			<?php foreach( $apres as $index => $apre ):?>
				<?php
					///Calcul de l'age des bénéficiaires
					if( !empty( $apre ) ){
						$dtnai = Set::classicExtract( $apre, 'Personne.dtnai' );
						$today = ( date( 'Y' ) );
						if( !empty( $dtnai ) ){
							$age = ($today - $dtnai);
						}
					}

					///récupération des aides liées à l'APRE
					$aidesApre = array();
					$mtforfait = null;
					$naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );

					foreach( $naturesaide as $natureaide => $nombre ) {
						if( $nombre > 0 ) {
							$aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
						}
					}

					/**
					**  Mise en place de l'impossibilité de modifier/relancer/imprimer les APREs forfaitaires
					**  +
					**  Conditionnement des éléments à afficher selon le statut de l'APRE
					**/
					$statutApre = Set::classicExtract( $apre, 'Apre.statutapre' );
					if( $statutApre == 'C' ){
						$mtforfait = $mtforfait;
					}
					else if( $statutApre == 'F' ) {
						$mtforfait = Set::classicExtract( $apre, 'Apre.mtforfait' );
					}
					echo $xhtml->tableCells(
						array(
							h( Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
							h( Set::enum( Set::classicExtract( $apre, 'Personne.sexe' ), $sexe ) ),
							h( $age ),
							h( Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
							h( $locale->money( Set::classicExtract( $apre, 'Apre.mtforfait' ) + Set::classicExtract( $apre, 'Apre.montantaides' ) ) ),
							( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ),
							h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $options['activitebeneficiaire'] ) ),
							h( Set::enum( Set::classicExtract( $apre, 'Apre.secteuractivite' ), $sect_acti_emp ) ),
							h( Set::enum( $statutApre , $options['statutapre'] ) ),
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
					///Nb total des montants versés
					$montantTotal += $mtforfait;
				?>
			<?php endforeach; ?>

			</tbody>
		</table>

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
					array( 'controller' => 'repsddtefp', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
		<?php echo $pagination;?>

	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif;?>