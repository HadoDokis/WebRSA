<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Relances de la personne';?></h1>

	<?php if( $permissions->check( 'orientsstructs', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'add', $personne_id ),
					empty( $erreurs )
				).' </li>';
			?>
		</ul>
	<?php endif;?>

	<?php if( !empty( $erreurs ) ):?>
		<div class="error_message">
			<?php if( count( $erreurs ) > 1 ):?>
			<ul>
				<?php foreach( $erreurs as $erreur ):?>
					<li><?php echo __d( 'relancenonrespectsanctionep93', "Erreur.{$erreur}", true );?></li>
				<?php endforeach;?>
			</ul>
			<?php else:?>
				<p><?php echo __d( 'relancenonrespectsanctionep93', "Erreur.{$erreurs[0]}", true );?></p>
			<?php endif;?>
		</div>
	<?php endif;?>

	<?php if( empty( $relances ) ):?>
		<p class="notice">Cette personne ne possède pas de relance à l'heure actuelle.</p>
	<?php else:?>
		<table>
			<thead>
				<tr>
					<th>N° CAF</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Ville</th>
					<th>Origine</th>
					<th>Date pivot</th>
					<th>Nombre de jours</th>
					<th>Date de relance</th>
					<th>Statut relance</th>
					<th class="actions" colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $relances as $i => $relance ):?>
					<?php
						if( isset( $relance['Orientstruct']['id'] ) && !empty( $relance['Orientstruct']['id'] ) ) {
							$date = date_short( @$relance['Orientstruct']['date_valid'] );
							$nbjours = round( ( mktime() - strtotime( @$relance['Orientstruct']['date_valid'] ) ) / ( 60 * 60 * 24 ) );
							$origine = 'Non contractualisation';
						}
						else {
							$date = date_short( @$relance['Contratinsertion']['df_ci'] );
							$nbjours = round( ( mktime() - strtotime( @$relance['Contratinsertion']['df_ci'] ) ) / ( 60 * 60 * 24 ) );
							$origine = 'Non renouvellement';
						}
					?>
					<tr class="<?php echo ( ( $i %2 ) ? 'even' : 'odd' );?>">
						<td><?php echo h( @$personne['Foyer']['Dossier']['matricule'] );?></td>
						<td><?php echo h( @$personne['Personne']['nom'] );?></td>
						<td><?php echo h( @$personne['Personne']['prenom'] );?></td>
						<td><?php echo h( @$personne['Foyer']['Adressefoyer'][0]['Adresse']['localite'] );?></td>
						<td><?php echo h( $origine );?></td>
						<td><?php echo h( $date );?></td>
						<td><?php echo h( $nbjours );?></td>
						<td><?php echo date_short( @$relance['Relancenonrespectsanctionep93']['daterelance'] );?></td>
						<td><?php
							if( @$relance['Relancenonrespectsanctionep93']['numrelance'] == 1 ) {
								echo '1ère relance';
							}
							else {
								echo "{$relance['Relancenonrespectsanctionep93']['numrelance']}ème relance";
							}
						?></td>
						<td><?php echo $xhtml->viewLink( 'Voir', array( '#' ), false );?></td>
						<td><?php echo $xhtml->printLink(
								'Imprimer',
								array( 'controller' => $this->params['controller'], 'action' => 'impression_individuelle', $relance['Pdf']['id'] ),
								/*$permissions->check( 'gedooos', 'orientstruct' ) && */$relance['Pdf']['id']
						);?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>

	<?php /*debug( $relances );*/ ?>
	<?php /*debug( $personne );*/ ?>
</div>
<div class="clearer"><hr /></div>