<?php
	$this->pageTitle = 'Recherche par Rendez-vous';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	$pagination = $this->Xpaginator->paginationBlock( 'Rendezvous', $this->passedArgs );
?>
<?php
	if( is_array( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
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
		observeDisableFieldsetOnCheckbox( 'CritererdvDaterdv', $( 'CritererdvDaterdvFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php echo $this->Form->create( 'Critererdv', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
<?php
		echo $this->Search->blocAllocataire();
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );
			echo $this->Search->natpf( $natpf );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'CritererdvReferentId', 'CritererdvStructurereferenteId' );
	});
</script>
	<fieldset>
		<legend>Recherche par RDV</legend>
			<?php echo $this->Form->input( 'Critererdv.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
			<?php echo $this->Form->input( 'Critererdv.statutrdv_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.statutrdv' ), 'type' => 'select' , 'options' => $statutrdv, 'empty' => true ) );?>
			<?php echo $this->Form->input( 'Critererdv.structurereferente_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_struct' ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>

			<?php echo $this->Form->input( 'Critererdv.referent_id', array( 'label' => __( 'Nom du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>

			<!--  Ajout d'une permanence liée à une structurereferente  -->
			<?php
				echo $this->Form->input( 'Critererdv.permanence_id', array( 'label' => 'Permanence liée à la structure', 'type' => 'select', 'options' => $permanences, 'empty' => true ) );
			?>
			<?php
				echo $this->Form->input( 'Critererdv.typerdv_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_rdv' ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) );
				// Thématiques du RDV
				if( isset( $thematiquesrdvs ) && !empty( $thematiquesrdvs ) ) {
					foreach( $thematiquesrdvs as $typerdv_id => $thematiques ) {
						$input = $this->Xform->input(
							'Critererdv.thematiquerdv_id',
							array(
								'type' => 'select',
								'multiple' => 'checkbox',
								'options' => $thematiques,
								'label' => 'Thématiques'
							)
						);
						echo $this->Xhtml->tag(
							'fieldset',
							$input,
							array(
								'id' => "CritererdvThematiquerdvId{$typerdv_id}",
								'class' => 'invisible',
							)
						);
					}
				}
			?>
			<?php echo $this->Form->input( 'Critererdv.daterdv', array( 'label' => 'Filtrer par date de RDV', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de Rendez-vous</legend>
				<?php
					$daterdv_from = Set::check( $this->request->data, 'Critererdv.daterdv_from' ) ? Set::extract( $this->request->data, 'Critererdv.daterdv_from' ) : strtotime( '-1 week' );
					$daterdv_to = Set::check( $this->request->data, 'Critererdv.daterdv_to' ) ? Set::extract( $this->request->data, 'Critererdv.daterdv_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Critererdv.daterdv_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterdv_from ) );?>
				<?php echo $this->Form->input( 'Critererdv.daterdv_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'maxYear' => date( 'Y' ) + 5,  'selected' => $daterdv_to ) );?>
			</fieldset>
	</fieldset>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $this->Form->end();?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php if( isset( $thematiquesrdvs ) && !empty( $thematiquesrdvs ) ):?>
			<?php foreach( $thematiquesrdvs as $typerdv_id => $thematiques ):?>
				observeDisableFieldsetOnValue(
					'CritererdvTyperdvId',
					'CritererdvThematiquerdvId<?php echo $typerdv_id;?>', // FIXME
					[ '<?php echo $typerdv_id;?>' ],
					false,
					true
				);
			<?php endforeach;?>
		<?php endif;?>
	});
</script>

<!-- Résultats -->
<?php if( isset( $rdvs ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $rdvs ) && count( $rdvs ) > 0  ):?>

		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Structure référente', 'Rendezvous.structurereferente_id' );?></th>
					<th>Référent</th>
					<th><?php echo $this->Xpaginator->sort( 'Objet du RDV', 'Rendezvous.typerdv_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date du RDV', 'Rendezvous.daterdv' );?></th>
					<th>Heure du RDV</th>
					<th><?php echo $this->Xpaginator->sort( 'Statut du RDV', 'Rendezvous.statutrdv_id' );?></th>

					<th colspan="2" class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $rdvs as $index => $rdv ):?>
					<?php
						$title = $rdv['Dossier']['numdemrsa'];

						// TODO: code en commun avec Rendezvous
						$thematiques = Hash::extract( $rdv, 'Thematiquerdv.{n}.name' );
						$row = null;
						if( !empty( $thematiques ) ) {
							$row = '<tr>
								<th>'.__d( 'rendezvous', 'Thematiquerdv.name' ).'</th>
								<td><ul><li>'.implode( 'li', $thematiques ).'</li></ul></td>
							</tr>';
						}

						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
							<!-- <tr>
									<th>Commune de naissance</th>
									<td>'.$rdv['Personne']['nomcomnai'].'</td>
								</tr> -->
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $rdv['Personne']['dtnai'] ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$rdv['Adresse']['numcomptt'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$rdv['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.$rolepers[$rdv['Prestation']['rolepers']].'</td>
								</tr>
								'.$row.'
							</tbody>
						</table>';
						echo $this->Xhtml->tableCells(
							array(
								h( $rdv['Personne']['nom'].' '.$rdv['Personne']['prenom'] ),
								h( Set::extract( $rdv, 'Adresse.locaadr' ) ),
								h( Set::extract( $rdv, 'Structurereferente.lib_struc' ) ),
								h( Set::classicExtract( $rdv, 'Referent.qual' ).' '.Set::classicExtract( $rdv, 'Referent.nom' ).' '.Set::classicExtract( $rdv, 'Referent.prenom' ) ),
								h( Set::enum( Set::extract( $rdv, 'Rendezvous.typerdv_id' ), $typerdv ) ),
								h( $this->Locale->date( 'Date::short', $rdv['Rendezvous']['daterdv'] ) ),
								h( $this->Locale->date( 'Time::short', $rdv['Rendezvous']['heurerdv'] ) ),
								h( Set::enum( Set::extract( $rdv, 'Rendezvous.statutrdv_id' ), $statutrdv ) ),

								array(
									$this->Xhtml->viewLink(
										'Voir le dossier « '.$title.' »',
										array( 'controller' => 'rendezvous', 'action' => 'index', $rdv['Rendezvous']['personne_id'] )
									),
									array( 'class' => 'noprint' )
								),
								$this->Xhtml->printLink(
									'Imprimer la notification',
									array( 'controller' => 'rendezvous', 'action' => 'impression', $rdv['Rendezvous']['id'] )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criteresrdv', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
					$this->Permissions->check( 'criteresrdv', 'exportcsv' )
				);
			?></li>
		</ul>
	<?php echo $pagination;?>

	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>

<?php endif?>