<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Relances de la personne';?></h1>

	<?php if( $permissions->check( 'orientsstructs', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
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
					<th>Date d'orientation</th>
					<th>Nombre de jours depuis l'orientation</th>
					<th>Date de relance</th>
					<th>Statut relance</th>
					<th class="actions" colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $relances as $i => $relance ):?>
					<tr class="<?php echo ( ( $i %2 ) ? 'even' : 'odd' );?>">
						<td><?php echo h( @$relance['Orientstruct']['Personne']['Foyer']['Dossier']['matricule'] );?></td>
						<td><?php echo h( @$relance['Orientstruct']['Personne']['nom'] );?></td>
						<td><?php echo h( @$relance['Orientstruct']['Personne']['prenom'] );?></td>
						<td><?php echo h( @$relance['Orientstruct']['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['localite'] );?></td>
						<td><?php echo date_short( @$relance['Orientstruct']['date_valid'] );?></td>
						<td><?php echo round( ( mktime() - strtotime( @$relance['Orientstruct']['date_valid'] ) ) / ( 60 * 60 * 24 ) );?></td>
						<td><?php echo date_short( @$relance['Relancenonrespectsanctionep93'][0]['daterelance'] );?></td>
						<td><?php
							if( @$relance['Relancenonrespectsanctionep93'][0]['numrelance'] == 1 ) {
								echo '1ère relance';
							}
							else {
								echo "{$relance['Relancenonrespectsanctionep93'][0]['numrelance']}ème relance";
							}
						?></td>
						<td><?php echo $xhtml->viewLink( 'Voir', array( '#' ), false );?></td>
						<td><?php echo $xhtml->printLink( 'Imprimer', array( '#' ), false );?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>

	<?php /*debug( $relances );*/ ?>
</div>
<div class="clearer"><hr /></div>