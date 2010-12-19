<h1><?php	echo $this->pageTitle = 'Affichage séance d\'EP'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php
		echo '<li>'.$xhtml->editLink(
			__d('Seanceep','Seanceep.edit',true),
			array( 'controller' => 'seanceseps', 'action' => 'edit', $seanceep['Seanceep']['id'] )
		).' </li>';

		if( empty( $seanceep['Seanceep']['finalisee'] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::traiterep',true ),
				array( 'controller' => 'seanceseps', 'action' => 'traiterep', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::traiterep',true ).'</span></li>';
		}

		if( empty( $seanceep['Seanceep']['finalisee'] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::finaliserep',true ),
				array( 'controller' => 'seanceseps', 'action' => 'finaliserep', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::finaliserep',true ).'</span></li>';
		}

		if( $seanceep['Seanceep']['finalisee'] == 'ep' ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::traitercg',true ),
				array( 'controller' => 'seanceseps', 'action' => 'traitercg', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::traitercg',true ).'</span></li>';
		}

		if( $seanceep['Seanceep']['finalisee'] == 'ep' ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::finalisercg',true ),
				array( 'controller' => 'seanceseps', 'action' => 'finalisercg', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::traitercg',true ).'</span></li>';
		}
	?>
	</ul>
	<table>
		<tbody>
			<tr class="odd">
				<th><?php echo "Date de la séance";?></th>
				<td><?php echo isset( $seanceep['Seanceep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $seanceep['Seanceep']['dateseance'])) : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Nom de l'EP";?></th>
				<td><?php echo isset( $seanceep['Ep']['name'] ) ? $seanceep['Ep']['name'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Structure référente";?></th>
				<td><?php echo isset( $seanceep['Structurereferente']['lib_struc'] ) ? $seanceep['Structurereferente']['lib_struc'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Salle de la commision";?></th>
				<td><?php echo isset( $seanceep['Seanceep']['salle'] ) ? $seanceep['Seanceep']['salle'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Observations de la commision";?></th>
				<td><?php echo isset( $seanceep['Seanceep']['observations'] ) ? $seanceep['Seanceep']['observations'] : null ;?></td>
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
			<ul class="actionMenu">
				<?php
					if( empty( $seanceep['Seanceep']['finalisee'] ) ) {
						echo '<li>'.$xhtml->editLink(
							'Modifier',
							array( 'controller' => 'dossierseps', 'action' => 'choose', Set::classicExtract( $seanceep, 'Seanceep.id' ) )
						).' </li>';
					}
					else {
						echo '<li><span class="disabled"> Modifier</span></li>';
					}
				?>
			</ul>
			<div id="dossierseps">
				<?php
					foreach( $themes as $theme ) {
// 						debug( Set::flatten( $dossiers[$theme] ) );
						echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )."</h3>";
						echo $default->index(
							$dossiers[$theme],
							array(
								'Personne.qual',
								'Personne.nom',
								'Personne.prenom',
								'Personne.dtnai',
								'Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
								'Dossierep.created',
								'Dossierep.themeep',
								'Dossierep.etapedossierep',
							),
							array(
								'options' => $options
							)
						);
						echo "</div>";
					}
				?>
			</div>
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
	makeTabbed( 'dossierseps', 3 );
</script>
