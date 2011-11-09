<?php  $this->pageTitle = 'Dossier de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un CER';
	}
	else {
		$this->pageTitle = 'CER ';
		$foyer_id = $this->data['Personne']['foyer_id'];
	}
?>
<div class="with_treemenu">
	<h1 class="aere"><?php  echo 'CER  ';?></h1>
			<?php if( isset( $sanctionseps58 ) && !empty( $sanctionseps58 ) ):?>
				<h2>Signalements pour non respect du contrat</h2>
				<table class="tooltips">
					<thead>
						<tr>
							<th>Date début contrat</th>
							<th>Date fin contrat</th>
							<th>Date signalement</th>
							<th>État dossier EP</th>
							<th colspan="1" class="action">Actions</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach( $sanctionseps58 as $sanctionep58 ):?>
						<?php
							$etatdossierep = Set::enum( $sanctionep58['Passagecommissionep']['etatdossierep'], $optionsdossierseps['Passagecommissionep']['etatdossierep'] );
							if( empty( $etatdossierep ) ) {
								$etatdossierep = 'En attente';
							}
						?>
						<tr>
							<td><?php echo $locale->date( 'Locale->date', $sanctionep58['Contratinsertion']['dd_ci'] );?></td>
							<td><?php echo $locale->date( 'Locale->date', $sanctionep58['Contratinsertion']['df_ci'] );?></td>
							<td><?php echo $locale->date( 'Locale->date', $sanctionep58['Sanctionep58']['created'] );?></td>
							<td><?php echo h( $etatdossierep );?></td>
							<td class="action"><?php echo $default->button( 'delete', array( 'controller' => 'sanctionseps58', 'action' => 'deleteNonrespectcer', $sanctionep58['Sanctionep58']['id'] ), array( 'enabled' => ( empty( $sanctionep58['Passagecommissionep']['etatdossierep'] ) ), 'confirm' => 'Confirmer la suppession ?' ) );?></td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			<?php endif;?>
		<?php if( empty( $orientstruct ) ) :?>
			<p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
		<?php else:?>
			<?php if( $nbdossiersnonfinalisescovs > 0 ):?>
				<p class="notice">Cette personne possède un contrat d'engagement réciproque en attente de passage en COV.</p>
			<?php endif;?>

			<?php if( empty( $contratsinsertion ) ):?>
				<p class="notice">Cette personne ne possède pas encore de contrat d'engagement réciproque.</p>
			<?php endif;?>

			<?php if( $permissions->check( 'proposcontratsinsertioncovs58', 'add' ) && $nbdossiersnonfinalisescovs == 0 ):?>
				<ul class="actionMenu">
					<?php
						echo '<li>'.$xhtml->addLink(
							'Ajouter un CER',
							array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'add', $personne_id )
						).' </li>';
					?>
				</ul>
			<?php endif;?>
		<?php endif;?>

	<?php if( Configure::read( 'Cg.departement' ) == 58 && isset( $propocontratinsertioncov58 ) && !empty( $propocontratinsertioncov58 ) ):?>
		<h2>Contrat en cours de validation par la commission d'orientation et de validation</h2>
		<table class="aere">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de début du contrat</th>
					<th>Date de fin du contrat</th>
					<th><?php echo __d( 'contratinsertion', 'Contratinsertion.num_contrat' ); ?></th>
					<th>État du dossier en COV</th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo h( $propocontratinsertioncov58['Personne']['nom'] );?></td>
					<td><?php echo h( $propocontratinsertioncov58['Personne']['prenom'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $propocontratinsertioncov58['Propocontratinsertioncov58']['dd_ci'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $propocontratinsertioncov58['Propocontratinsertioncov58']['df_ci'] );?></td>
					<?php if ( isset( $propocontratinsertioncov58['Propocontratinsertioncov58']['avenant_id'] ) && !empty( $propocontratinsertioncov58['Propocontratinsertioncov58']['avenant_id'] ) ) { ?>
						<td>Avenant</td>
					<?php } else { ?>
						<td><?php echo h( Set::enum( $propocontratinsertioncov58['Propocontratinsertioncov58']['num_contrat'], $optionsdossierscovs58['Propocontratinsertioncov58']['num_contrat'] ) );?></td>
					<?php } ?>
					<td><?php echo h( Set::enum( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'], $optionsdossierscovs58['Passagecov58']['etatdossiercov'] ) );?></td>
					<td><?php echo $default->button( 'edit', array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'edit', $propocontratinsertioncov58['Personne']['id'] ), array( 'enabled' => ( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'] != 'associe' ) ) );?></td>
					<td><?php echo $default->button( 'delete', array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'delete', $propocontratinsertioncov58['Personne']['id'] ), array( 'enabled' => ( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'] != 'associe' ) ), 'Confirmer ?' );?></td>
				</tr>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( !empty( $contratsinsertion ) ):?>
		<h2>Contrats effectifs</h2>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Rang contrat</th>
					<th>Date début</th>
					<th>Date fin</th>
					<th>Décision</th>
					<th colspan="7" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $contratsinsertion as $contratinsertion ):?>
					<?php
						$dureeTolerance = Configure::read( 'Sanctionep58.nonrespectcer.dureeTolerance' );

						$enCours = (
							( strtotime( $contratinsertion['Contratinsertion']['dd_ci'] ) <= mktime() )
							&& ( strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) + ( $dureeTolerance * 24 * 60 * 60 ) >= mktime() )
						);

						$isValid = Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' );
						$block = true;
						if( $isValid == 'V'  ){
							$block = false;
						}

						$contratenep = in_array( $contratinsertion['Contratinsertion']['id'], $contratsenep );

						if ( isset( $contratinsertion['Contratinsertion']['avenant_id'] ) && !empty( $contratinsertion['Contratinsertion']['avenant_id'] ) ) {
							$numcontrat = 'Avenant';
						}
						else {
							$numcontrat = h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) );
						}

						echo $xhtml->tableCells(
							array(
								$numcontrat,
								h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
								h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
								h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
								$xhtml->viewLink(
									'Voir le CER',
									array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
									$permissions->check( 'contratsinsertion', 'view' )
								),
								$xhtml->editLink(
									'Éditer le CER ',
									array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
									$block
									&& $permissions->check( 'contratsinsertion', 'edit' )
								),
								$xhtml->printLink(
									'Imprimer le CER',
									array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] ),
									$permissions->check( 'gedooos', 'contratinsertion' )
								),
								$xhtml->deleteLink(
									'Supprimer le CER ',
									array( 'controller' => 'contratsinsertion', 'action' => 'delete', $contratinsertion['Contratinsertion']['id'] ),
									$permissions->check( 'contratsinsertion', 'delete' )
								),
								$xhtml->fileLink(
									'Fichiers liés',
									array( 'controller' => 'contratsinsertion', 'action' => 'filelink', $contratinsertion['Contratinsertion']['id'] ),
									$permissions->check( 'contratsinsertion', 'filelink' )
								),
								$xhtml->saisineEpLink(
									'Sanction',
									array( 'controller' => 'sanctionseps58', 'action' => 'nonrespectcer', $contratinsertion['Contratinsertion']['id'] ),
									$permissions->check( 'sanctionseps58', 'nonrespectcer' )
									&& $enCours
									&& !$block
									&& ( $contratinsertion['Contratinsertion']['forme_ci'] == 'S' )
									&& ( !isset( $sanctionseps58 ) || empty( $sanctionseps58 ) )
									&& empty( $erreursCandidatePassage )
									&& !$contratenep
								),
								$xhtml->avenantLink(
									'Créer un avenant',
									array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'add', $personne_id, $contratinsertion['Contratinsertion']['id'] ),
									$permissions->check( 'contratsinsertion', 'add' )
									&& ( $contratinsertion['Contratinsertion']['id'] == $contratsinsertion[0]['Contratinsertion']['id'] )
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
</div>
<div class="clearer"><hr /></div>
