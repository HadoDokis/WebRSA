<?php
	$title_for_layout = 'Contrats d\'engagement réciproque';
	$this->set( 'title_for_layout', $title_for_layout );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<?php echo $this->Html->tag( 'h1', $title_for_layout );?>

	<?php if( !empty( $signalementseps93 ) ):?>
		<h2>Signalements pour non respect du contrat</h2>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Date début contrat</th>
					<th>Date fin contrat</th>
					<th>Date signalement</th>
					<th>Rang signalement</th>
					<th>État dossier EP</th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach( $signalementseps93 as $signalementep93 ):?>
				<?php
					$etatdossierep = Set::enum( $signalementep93['Passagecommissionep']['etatdossierep'], $optionsdossierseps['Passagecommissionep']['etatdossierep'] );
					if( empty( $etatdossierep ) ) {
						$etatdossierep = 'En attente';
					}
				?>
				<tr>
					<td><?php echo $this->Locale->date( 'Locale->date', $signalementep93['Contratinsertion']['dd_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $signalementep93['Contratinsertion']['df_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $signalementep93['Signalementep93']['date'] );?></td>
					<td><?php echo h( $signalementep93['Signalementep93']['rang'] );?></td>
					<td><?php echo h( $etatdossierep );?></td>
					<td class="action"><?php echo $this->Default->button( 'edit', array( 'controller' => 'signalementseps', 'action' => 'edit', $signalementep93['Signalementep93']['id'] ), array( 'enabled' => ( empty( $signalementep93['Passagecommissionep']['etatdossierep'] ) ) ) );?></td>
					<td class="action"><?php echo $this->Default->button( 'delete', array( 'controller' => 'signalementseps', 'action' => 'delete', $signalementep93['Signalementep93']['id'] ), array( 'enabled' => ( empty( $signalementep93['Passagecommissionep']['etatdossierep'] ) ), 'confirm' => 'Confirmer la suppression du signalement ?' ) );?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( !empty( $contratscomplexeseps93 ) ):?>
		<h2>Passages en EP pour contrats complexes</h2>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Date début contrat</th>
					<th>Date fin contrat</th>
					<th>Date de création du dossier d'EP</th>
					<th>État dossier EP</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach( $contratscomplexeseps93 as $contratcomplexeep93 ):?>
				<?php
					$etatdossierep = Set::enum( $contratcomplexeep93['Passagecommissionep']['etatdossierep'], $optionsdossierseps['Passagecommissionep']['etatdossierep'] );
					if( empty( $etatdossierep ) ) {
						$etatdossierep = 'En attente';
					}
				?>
				<tr>
					<td><?php echo $this->Locale->date( 'Locale->date', $contratcomplexeep93['Contratinsertion']['dd_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $contratcomplexeep93['Contratinsertion']['df_ci'] );?></td>
					<td><?php echo $this->Locale->date( 'Locale->date', $contratcomplexeep93['Contratcomplexeep93']['created'] );?></td>
					<td><?php echo h( $etatdossierep );?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>

	<ul class="actionMenu">
		<li><?php
				echo $this->Xhtml->addLink(
					'Ajouter',
					array(
						'action' => 'add',
						$personne_id
					),
					$disabledLinks['Cers93::add']
				);
			?>
		</li>
	</ul>

	<?php if( !empty( $erreursCandidatePassage ) ):?>
		<h2>Raisons pour lesquelles le contrat ne peut pas être signalé</h2>
		<div class="error_message">
			<?php if( count( $erreursCandidatePassage ) > 1 ):?>
			<ul>
				<?php foreach( $erreursCandidatePassage as $erreur ):?>
					<li><?php echo __d( 'relancenonrespectsanctionep93', "Erreur.{$erreur}" );?></li>
				<?php endforeach;?>
			</ul>
			<?php else:?>
				<p><?php echo __d( 'relancenonrespectsanctionep93', "Erreur.{$erreursCandidatePassage[0]}" );?></p>
			<?php endif;?>
		</div>
	<?php endif;?>

	<?php
		echo $this->Default2->index(
			$cers93,
			array(
				'Cer93.positioncer' => array( 'domain' => 'cer93' ),
				'Cer93.formeci' => array( 'domain' => 'cer93' ),
				'Contratinsertion.dd_ci',
				'Contratinsertion.df_ci',
				'Contratinsertion.rg_ci',
				'Contratinsertion.decision_ci',
				'Contratinsertion.datedecision',
				'Fichiermodule.nb_fichiers_lies' => array( 'label' => 'Nb fichiers liés', 'type' => 'text' )
			),
			array(
				'actions' => array(
					'Cers93::view' => array( 'url' => array( 'action' => 'view', '#Contratinsertion.id#' ) ),
					'Cers93::edit' => array(
						'url' => array( 'action' => 'edit', '#Contratinsertion.id#' ),
						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'cers93', 'edit' ), $disabledLinks['Cers93::edit'] )
					),
					'Cers93::signature' => array(
						'url' => array( 'action' => 'signature', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'cers93', 'signature' ), $disabledLinks['Cers93::signature'] )
					),
					'Histoschoixcers93::attdecisioncpdv' => array(
						'url' => array( 'action' => 'attdecisioncpdv', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'attdecisioncpdv' ), $disabledLinks['Histoschoixcers93::attdecisioncpdv'] )
					),
					'Histoschoixcers93::attdecisioncg' => array(
						'url' => array( 'action' => 'attdecisioncg', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'attdecisioncg' ), $disabledLinks['Histoschoixcers93::attdecisioncg'] )
					),
					'Histoschoixcers93::premierelecture' => array(
						'url' => array( 'action' => 'premierelecture', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'premierelecture' ), $disabledLinks['Histoschoixcers93::premierelecture'] )
					),
					'Histoschoixcers93::secondelecture' => array(
						'url' => array( 'action' => 'secondelecture', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'secondelecture' ), $disabledLinks['Histoschoixcers93::secondelecture'] )
					),
					'Histoschoixcers93::aviscadre' => array(
						'url' => array( 'action' => 'aviscadre', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'aviscadre' ), $disabledLinks['Histoschoixcers93::aviscadre'] )
					),
					'Cers93::impression' => array( 'url' => array( 'action' => 'impression', '#Contratinsertion.id#' ) ),
					'Signalementseps::add' => array(
						'label' => 'Signalement',
						'url' => array( 'controller' => 'signalementseps', 'action' => 'add', '#Contratinsertion.id#' ),
						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'signalementseps', 'add' ), $disabledLinks['Signalementseps::add'] ),
						'class' => 'signalementseps add'
					),
					'Contratsinsertion::filelink' => array( 'url' => array( 'action' => 'filelink', '#Contratinsertion.id#' ) )
				),
				'options' => $options
			)
		);
	?>
</div>
<div class="clearer"><hr /></div>