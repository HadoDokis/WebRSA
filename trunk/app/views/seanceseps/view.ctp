<h1><?php	echo $this->pageTitle = '1. Affichage séance d\'EP'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php

		echo '<li>'.$xhtml->editLink(
			__d('Seanceep','Seanceep.edit',true),
			array( 'controller' => 'seanceseps', 'action' => 'edit', $seanceep['Seanceep']['id'] )
		).' </li>';

		if( empty( $seanceep['Seanceep']['finalisee'] ) && $countDossiers > 0 && !empty($membresepsseanceseps) ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::traiterep',true ),
				array( 'controller' => 'seanceseps', 'action' => 'traiterep', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::traiterep',true ).'</span></li>';
		}

		if( empty( $seanceep['Seanceep']['finalisee'] ) && $countDossiers > 0 && !empty($membresepsseanceseps) ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::finaliserep',true ),
				array( 'controller' => 'seanceseps', 'action' => 'finaliserep', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::finaliserep',true ).'</span></li>';
		}

		$presencesComite = Set::extract( $membresepsseanceseps, '/MembreepSeanceep/presence' );
		$presencesComite = Set::filter( $presencesComite );
		if( !empty( $seanceep['Seanceep']['finalisee'] ) && !empty( $presencesComite ) && count( $presencesComite ) == count( $membresepsseanceseps ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'seanceep','Seanceseps::impressionpv', true ),
				array( 'controller' => 'seanceseps', 'action' => 'impressionpv', $seanceep['Seanceep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::impressionpv',true ).'</span></li>';
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
			echo '<li><span class="disabled"> '.__d( 'seanceep','Seanceseps::finalisercg',true ).'</span></li>';
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
			<h2 class="title">2. Liste des participants</h2>
			<div>
				<ul class="actionMenu">
				<?php
					echo '<li>'.$xhtml->editLink(
						__d('Seanceep','Seanceep.edit',true),
						array( 'controller' => 'membreseps', 'action' => 'editliste', $seanceep['Seanceep']['ep_id'], $seanceep['Seanceep']['id'] ),
						( $seanceep['Seanceep']['finalisee'] == '' )
					).' </li>';

					echo '<li>'.$xhtml->presenceLink(
						__d('Seanceep','Seanceep::presence',true),
						array( 'controller' => 'membreseps', 'action' => 'editpresence', $seanceep['Seanceep']['ep_id'], $seanceep['Seanceep']['id'] ),
						( $seanceep['Seanceep']['finalisee'] == 'ep' )
					).' </li>';
				?>
				</ul>
			<?php
				echo "<table>";
				echo $html->tag(
					'thead',
					$default->thead(
						array(
							'Membreep.Fonctionmembreep.name',
							'Membreep.nom',
							'MembreepSeanceep.reponse',
							'MembreepSeanceep.presence'
						)
					)
				);
				echo "<tbody>";
				foreach($membresepsseanceseps as $membreepseanceep) {
					echo "<tr>";
						echo $html->tag(
							'td',
							$membreepseanceep['Membreep']['Fonctionmembreep']['name']
						);
						echo $html->tag(
							'td',
							implode(' ', array($membreepseanceep['Membreep']['qual'], $membreepseanceep['Membreep']['nom'], $membreepseanceep['Membreep']['prenom']))
						);
						$membreepseanceep['MembreepSeanceep']['reponsetxt'] = "";
						if (!empty($membreepseanceep['MembreepSeanceep']['reponse'])) {
							$membreepseanceep['MembreepSeanceep']['reponsetxt'] = __d('membreep_seanceep', 'ENUM::REPONSE::'.$membreepseanceep['MembreepSeanceep']['reponse'], true);
							if ($membreepseanceep['MembreepSeanceep']['reponse']=='remplacepar')
								$membreepseanceep['MembreepSeanceep']['reponsetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
						}
						echo $html->tag(
							'td',
							$membreepseanceep['MembreepSeanceep']['reponsetxt']
						);
						$membreepseanceep['MembreepSeanceep']['presencetxt'] = "";
						if (!empty($membreepseanceep['MembreepSeanceep']['presence'])) {
							$membreepseanceep['MembreepSeanceep']['presencetxt'] = __d('membreep_seanceep', 'ENUM::PRESENCE::'.$membreepseanceep['MembreepSeanceep']['presence'], true);
							if ($membreepseanceep['MembreepSeanceep']['presence']=='remplacepar')
								$membreepseanceep['MembreepSeanceep']['presencetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
						}
						echo $html->tag(
							'td',
							$membreepseanceep['MembreepSeanceep']['presencetxt']
						);
					echo "</tr>";
				}
				echo "</tbody></table>";
			?>
			</div>
		</div>
	<?php endif;?>
		<div id="dossiers">
			<h2 class="title">3. Liste des dossiers</h2>
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
