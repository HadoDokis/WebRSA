<?php  $this->pageTitle = 'Dossier de la personne';?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un CER';
	}
	else {
		$this->pageTitle = 'CER ';
	}
?>
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
					<td><?php echo $this->Locale->date( 'Locale->date', $sanctionep58['Contratinsertion']['dd_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $sanctionep58['Contratinsertion']['df_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $sanctionep58['Sanctionep58']['created'] );?></td>
					<td><?php echo h( $etatdossierep );?></td>
					<td class="action"><?php echo $this->Default->button( 'delete', array( 'controller' => 'sanctionseps58', 'action' => 'deleteNonrespectcer', $sanctionep58['Sanctionep58']['id'] ), array( 'enabled' => ( empty( $sanctionep58['Passagecommissionep']['etatdossierep'] ) ), 'confirm' => 'Confirmer la suppession ?' ) );?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( empty( $orientstruct ) ) :?>
		<p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
	<?php elseif( !empty( $bloquageAjoutCER ) ) :?>
		<p class="error">Cette personne possède actuellement une orientation professionnelle. Impossible de créer un CER.<br /> Une réorientation sociale doit être sollicitée pour pouvoir enregistrer un CER.</p>
	<?php elseif( empty( $soumisADroitEtDevoir ) ) :?>
		<p class="error">Cette personne n'est pas soumise à droit et devoir. Impossible de créer un CER.</p>
	<?php elseif( $nbDemandedemaintienNonfinalisesCovs == 0 ) :?>
		<p class="error">Cette personne n'a pas encore été présentée en demande de maintien en social. Impossible de créer un CER.</p>
	<?php else:?>
		<?php if( $nbdossiersnonfinalisescovs > 0 ):?>
			<p class="notice">Cette personne possède un contrat d'engagement réciproque en attente de passage en COV.</p>
		<?php endif;?>

		<?php if( empty( $contratsinsertion ) ):?>
			<p class="notice">Cette personne ne possède pas encore de contrat d'engagement réciproque.</p>
		<?php endif;?>

		<?php  /*if( !empty( $orientstructEmploi ) ) :?>
			<p class="error">Cette personne possède actuellement une orientation professionnelle. Une réorientation sociale doit être sollicitée pour pouvoir enregistrer un CER.</p>
		<?php endif;*/ ?>

		<?php if( $this->Permissions->checkDossier( 'proposcontratsinsertioncovs58', 'add', $dossierMenu ) && $nbdossiersnonfinalisescovs == 0 ):?>
			<ul class="actionMenu">
				<?php
					$block = empty( $orientstruct ) || !empty( $orientstructEmploi );
					echo '<li>'.$this->Xhtml->addLink(
						'Ajouter un CER',
						array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'add', $personne_id ),
						( !$block )
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
				<th>Date de début du contrat proposée</th>
				<th>Date de fin du contrat proposée</th>
				<th><?php echo __d( 'contratinsertion', 'Contratinsertion.num_contrat' ); ?></th>
				<th>État du dossier en COV</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo h( $propocontratinsertioncov58['Personne']['nom'] );?></td>
				<td><?php echo h( $propocontratinsertioncov58['Personne']['prenom'] );?></td>
				<td><?php echo $this->Locale->date( __( 'Date::short' ), $propocontratinsertioncov58['Propocontratinsertioncov58']['dd_ci'] );?></td>
				<td><?php echo $this->Locale->date( __( 'Date::short' ), $propocontratinsertioncov58['Propocontratinsertioncov58']['df_ci'] );?></td>
				<?php if ( isset( $propocontratinsertioncov58['Propocontratinsertioncov58']['avenant_id'] ) && !empty( $propocontratinsertioncov58['Propocontratinsertioncov58']['avenant_id'] ) ) { ?>
					<td>Avenant</td>
				<?php } else { ?>
					<td><?php echo h( Set::enum( $propocontratinsertioncov58['Propocontratinsertioncov58']['num_contrat'], $optionsdossierscovs58['Propocontratinsertioncov58']['num_contrat'] ) );?></td>
				<?php } ?>
				<td><?php echo h( Set::enum( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'], $optionsdossierscovs58['Passagecov58']['etatdossiercov'] ) );?></td>
				<td><?php echo $this->Default->button( 'edit', array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'edit', $propocontratinsertioncov58['Personne']['id'] ), array( 'enabled' => ( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'] != 'associe' ) ) );?></td>
				<td><?php echo $this->Default->button( 'delete', array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'delete', $propocontratinsertioncov58['Propocontratinsertioncov58']['id'] ), array( 'enabled' => empty( $propocontratinsertioncov58['Passagecov58']['etatdossiercov'] ) ), 'Confirmer ?' );?></td>
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
				<th>COV ayant traitée le dossier</th>
				<th>Observation de la COV</th>
				<th colspan="8" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $contratsinsertion as $contratinsertion ):?>
				<?php
// debug($contratinsertion);
					$dureeTolerance = Configure::read( 'Sanctionep58.nonrespectcer.dureeTolerance' );

					$enCours = (
						( strtotime( $contratinsertion['Contratinsertion']['dd_ci'] ) <= time() )
						&& ( strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) + ( $dureeTolerance * 24 * 60 * 60 ) >= time() )
					);

					$isValid = Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' );
					$block = true;
// 						if( $isValid == 'V'  ){
// 							$block = false;
// 						}

					$block = empty( $orientstruct ) || !empty( $orientstructEmploi );

					$contratenep = in_array( $contratinsertion['Contratinsertion']['id'], $contratsenep );

					if ( isset( $contratinsertion['Contratinsertion']['avenant_id'] ) && !empty( $contratinsertion['Contratinsertion']['avenant_id'] ) ) {
						$numcontrat = 'Avenant';
					}
					else {
						$numcontrat = h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) );
					}


					$infoscov = '';
					if( isset( $contratinsertion['Cov58']['datecommission'] ) && !empty( $contratinsertion['Cov58']['datecommission'] ) ){
						$infoscov = 'Site "'.Set::classicExtract( $contratinsertion, 'Sitecov58.name' ).'", le '.$this->Locale->date( "Datetime::full", Set::classicExtract( $contratinsertion, 'Cov58.datecommission' ) );

					}

					echo $this->Xhtml->tableCells(
						array(
							$numcontrat,
							h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
							h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
							h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$this->Locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
							h( $infoscov ),
							h( Set::classicExtract( $contratinsertion, 'Decisionpropocontratinsertioncov58.commentaire' ) ),
							$this->Xhtml->viewLink(
								'Voir le CER',
								array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
								$this->Permissions->checkDossier( 'contratsinsertion', 'view', $dossierMenu )
							),
							$this->Xhtml->editLink(
								'Éditer le CER ',
								array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
								!$block
								&& $this->Permissions->checkDossier( 'contratsinsertion', 'edit', $dossierMenu )
							),
							$this->Xhtml->printLink(
								'Imprimer le CER',
								array( 'controller' => 'contratsinsertion', 'action' => 'impression', $contratinsertion['Contratinsertion']['id'] ),
								$this->Permissions->checkDossier( 'contratsinsertion', 'impression', $dossierMenu )
							),
							$this->Xhtml->deleteLink(
								'Supprimer le CER ',
								array( 'controller' => 'contratsinsertion', 'action' => 'delete', $contratinsertion['Contratinsertion']['id'] ),
								$this->Permissions->checkDossier( 'contratsinsertion', 'delete', $dossierMenu )
							),
							$this->Xhtml->saisineEpLink(
								'Sanction',
								array( 'controller' => 'sanctionseps58', 'action' => 'nonrespectcer', $contratinsertion['Contratinsertion']['id'] ),
								$this->Permissions->checkDossier( 'sanctionseps58', 'nonrespectcer', $dossierMenu )
								&& $enCours
								&& ( !isset( $sanctionseps58 ) || empty( $sanctionseps58 ) )
								&& empty( $erreursCandidatePassage )
								&& !$contratenep
							),
							$this->Xhtml->avenantLink(
								'Créer un avenant',
								array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'add', $personne_id, $contratinsertion['Contratinsertion']['id'] ),
								$this->Permissions->checkDossier( 'contratsinsertion', 'add', $dossierMenu )
								&& ( $contratinsertion['Contratinsertion']['id'] == $contratsinsertion[0]['Contratinsertion']['id'] )
								&& ( !$block )
							),
							$this->Xhtml->fileLink(
								'Fichiers liés',
								array( 'controller' => 'contratsinsertion', 'action' => 'filelink', $contratinsertion['Contratinsertion']['id'] ),
								$this->Permissions->checkDossier( 'contratsinsertion', 'filelink', $dossierMenu )
							),
							h( '('.Set::classicExtract( $contratinsertion, 'Fichiermodule.nb_fichiers_lies' ).')' )
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
		</tbody>
	</table>
<?php  endif;?>