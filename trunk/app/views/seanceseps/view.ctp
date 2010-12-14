<h1><?php	echo $this->pageTitle = 'Affichage séance d\'EP'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php 
		echo '<li>'.$xhtml->editLink(
			__d('Seanceep','Seanceep.edit',true),
			array( 'controller' => 'seanceseps', 'action' => 'edit', $seanceep['Seanceep']['id'] )
		).' </li>';
	?>				
	</ul>
	<table>
		<tbody>
			<tr class="even">
				<th><?php echo "Nom de l'EP";?></th>
				<td><?php echo isset( $seanceep['Ep']['name'] ) ? $seanceep['Ep']['name'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Regroupement";?></th>
				<td><?php echo isset( $seanceep['Ep']['Regroupementep']['name'] ) ? $seanceep['Ep']['Regroupementep']['name'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Structure référente";?></th>
				<td><?php echo isset( $seanceep['Structurereferente']['lib_struc'] ) ? $seanceep['Structurereferente']['lib_struc'] : null ;?></td>
			</tr>			
			<tr class="odd">
				<th><?php echo "Date de la séance";?></th>
				<td><?php echo isset( $seanceep['Seanceep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $seanceep['Seanceep']['dateseance'])) : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Décision finale";?></th>
				<td><?php echo isset( $seanceep['Seanceep']['finalisee'] ) ? $seanceep['Seanceep']['finalisee'] : null ;?></td>
			</tr>
		</tbody>
	</table>
</div>
<br />
<div id="tabbedWrapper" class="tabs">
	<?php if( isset( $membresepsseanceseps ) ):?>
		<div id="participants">
			<h2 class="title">Liste des participants</h2>
			<div>
				<ul class="actionMenu">
				<?php 
					echo '<li>'.$xhtml->editLink(
						__d('Seanceep','Seanceep.edit',true),
						array( 'controller' => 'membreseps', 'action' => 'editliste', $seanceep['Seanceep']['ep_id'], $seanceep['Seanceep']['id'] )
					).' </li>';
					echo '<li>'.$xhtml->presenceLink(
						__d('Seanceep','Seanceep.presence',true),
						array( 'controller' => 'membreseps', 'action' => 'editpresence', $seanceep['Seanceep']['ep_id'], $seanceep['Seanceep']['id'] )
					).' </li>';					
					
				?>				
				</ul>
			<?php
				echo $default2->index(
					$membresepsseanceseps,
					array(
						'Membreep.Fonctionmembreep.name',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'MembreepSeanceep.reponse',
						'MembreepSeanceep.presence'
					),
					array(
						'groupColumns' => array(
							'Participants' => array(0, 1, 2, 3),
							'Présences' => array(4,5)
						),
						'actions' => array(
							'membreseps_seanceseps::edit',
							'membreseps_seanceseps::delete'
						),
						'options' => $options
					)
				);	
			?>
			</div>
		</div>
	<?php endif;?>	
		<div id="dossiers">
			<h2 class="title">Liste des dossiers</h2>
			<div>--</div>
		</div>
		
		<div id="reorientations">
			<h2 class="title">Réorientations</h2>
			<div>--</div>
		</div>
</div>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>