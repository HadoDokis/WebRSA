<?php
	$this->pageTitle = 'Comité examen APRE';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1>Recherche de Comité d'examen</h1>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addComiteLink(
				'Ajouter Comité',
				array( 'controller' => 'comitesapres', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'ComiteapreDatecomite', $( 'ComiteapreDatecomiteFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'ComiteapreHeurecomite', $( 'ComiteapreHeurecomiteFromHour' ).up( 'fieldset' ), false );
	});
</script>

<?php $pagination = $xpaginator->paginationBlock( 'Comiteapre', $this->passedArgs ); ?>
<?php echo $xform->create( 'Comiteapre', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
		<?php echo $xform->input( 'Comiteapre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

		<?php echo $xform->input( 'Comiteapre.datecomite', array( 'label' => 'Filtrer par date de Comité d\'examen', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de Comité</legend>
			<?php
				$datecomite_from = Set::check( $this->data, 'Comiteapre.datecomite_from' ) ? Set::extract( $this->data, 'Comiteapre.datecomite_from' ) : strtotime( '-1 week' );
				$datecomite_to = Set::check( $this->data, 'Comiteapre.datecomite_to' ) ? Set::extract( $this->data, 'Comiteapre.datecomite_to' ) : strtotime( 'now' );
			?>
			<?php echo $xform->input( 'Comiteapre.datecomite_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_from ) );?>
			<?php echo $xform->input( 'Comiteapre.datecomite_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $datecomite_to ) );?>
		</fieldset>
	</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $xform->end();?>

<!-- Résultats -->
<?php if( isset( $comitesapres ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $comitesapres ) && count( $comitesapres ) > 0  ):?>
		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Intitulé du comité', 'Comiteapre.intitulecomite' );?></th>
					<th><?php echo $xpaginator->sort( 'Lieu du comité', 'Comiteapre.lieucomite' );?></th>
					<th><?php echo $xpaginator->sort( 'Date du comité', 'Comiteapre.datecomite' );?></th>
					<th><?php echo $xpaginator->sort( 'Heure du comité', 'Comiteapre.heurecomite' );?></th>
					<th colspan="3" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $comitesapres as $comiteapre ) {

						$decisionPrise = true;
						$decision = Set::filter( Set::extract( $comiteapre, '/Apre/ApreComiteapre/decisioncomite' ) );
						if( !empty( $decision ) ) {
							$decisionPrise = false;
						}

						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $comiteapre, 'Comiteapre.intitulecomite' ) ),
								h( Set::classicExtract( $comiteapre, 'Comiteapre.lieucomite' ) ),
								h( date_short( Set::classicExtract( $comiteapre, 'Comiteapre.datecomite' ) ) ),
								h( $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteapre.heurecomite' ) ) ),
								$xhtml->viewLink(
									'Voir le comité',
									array( 'controller' => 'comitesapres', 'action' => 'view', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
									$decisionPrise,
									$permissions->check( 'comitesapres', 'index' )
								),
								$xhtml->rapportLink(
									'Rapport',
									array( 'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
									$permissions->check( 'comitesapres', 'rapport' )
								),
								$xhtml->notificationsApreLink(
									'Notifier la décision',
									array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite', 'Cohortecomiteapre__id' => Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
									$permissions->check( 'cohortescomitesapres', 'notificationscomite' )
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
	<ul class="actionMenu">
		<li><?php
			echo $xhtml->exportLink(
				'Télécharger le tableau',
				array( 'controller' => 'comitesapres', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
			);
		?></li>
	</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun comité.</p>
	<?php endif?>
<?php endif?>