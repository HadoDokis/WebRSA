<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Paiement des allocations';?>

<h1><?php echo $this->pageTitle;?></h1>

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

<?php echo $form->create( 'Infosfinancieres', array( 'type' => 'post', 'action' => '/indexdossier/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
		<?php echo $form->input( 'Filtre.moismoucompta', array( 'label' => 'Recherche des paiements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => $annees['maxYear'], 'minYear' => $annees['minYear'] ) );?>
		<?php echo $form->input( 'Filtre.type_allocation', array( 'label' => 'Type d\'allocation', 'type' => 'select', 'options' => $type_allocation, 'empty' => true ) ); ?>
		<?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire', 'type' => 'text' ) ); ?>
		<?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Code INSEE', 'type' => 'text', 'maxlength' => 5 ) ); ?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $infosfinancieres ) ):?>
<?php $mois = strftime('%B %Y', strtotime( $this->data['Filtre']['moismoucompta']['year'].'-'.$this->data['Filtre']['moismoucompta']['month'].'-01' ) ); ?>

	<h2 class="noprint">Liste des allocations pour le mois de <?php echo isset( $mois ) ? $mois : null ; ?></h2>

	<?php if( is_array( $infosfinancieres ) && count( $infosfinancieres ) > 0  ):?>
	<?php /*echo $pagination;*/?>
	<?php require( 'index.pagination.ctp' )?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom/prénom du bénéficiaire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de naissance du bénéficiaire', 'Personne.dtnai' );?></th>
					<th>Type d'allocation</th>
					<th>Montant de l'allocation</th>
					<th class="action noprint">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php $even = true;?>
				<?php foreach( $infosfinancieres as $index => $infofinanciere ):?>
					<?php
						// Nouvelle entrée
						if( Set::extract( $infosfinancieres, ( $index - 1 ).'.Dossier.numdemrsa' ) != Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) ) {
							$rowspan = 1;
							for( $i = ( $index + 1 ) ; $i < count( $infosfinancieres ) ; $i++ ) {
								if( Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) == Set::extract( $infosfinancieres, $i.'.Dossier.numdemrsa' ) )
									$rowspan++;
							}
							if( $rowspan == 1 ) {
								echo $xhtml->tableCells(
									array(
										h( $infofinanciere['Dossier']['numdemrsa'] ),
										h( $infofinanciere['Dossier']['matricule'] ),
										h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
										$locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ),
										h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
										$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
										array(
											$xhtml->viewLink(
												'Voir les informations financières',
												array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_id'] ),
												$permissions->check( 'infosfinancieres', 'view' )
											),
											array( 'class' => 'noprint' )
										)
									),
									array( 'class' => ( $even ? 'even' : 'odd' ) ),
									array( 'class' => ( !$even ? 'even' : 'odd' ) )
								);
							}
							// Nouvelle entrée avec rowspan
							else {
								echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
										<td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['numdemrsa'] ).'</td>
										<td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['matricule'] ).'</td>
										<td rowspan="'.$rowspan.'">'.h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ).'</td>
										<td rowspan="'.$rowspan.'">'.$locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ).'</td>

										<td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
										<td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>
										<td rowspan="'.$rowspan.'" class="noprint">'. $xhtml->viewLink(
											'Voir les informations financières',
											array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_id'] ),
											$permissions->check( 'infosfinancieres', 'view' )
										).'</td>
									</tr>';
							}
						}
						// Suite avec rowspan
						else {
							echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
									<td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
									<td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>
								</tr>';
						}
						if( Set::extract( $infosfinancieres, ( $index + 1 ).'.Dossier.numdemrsa' ) != Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) ) {
							$even = !$even;
						}
					?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if( Set::extract( $paginator, 'params.paging.Infofinanciere.count' ) > 65000 ):?>
			<p style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $xhtml->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
		<?php endif;?>
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
					array( 'controller' => 'infosfinancieres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php require( 'index.pagination.ctp' )?>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>

<?php endif?>