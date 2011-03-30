<h1><?php	echo $this->pageTitle = '1. Affichage séance d\'EP'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php

		echo '<li>'.$xhtml->editLink(
			__d('Commissionep','Commissionep.edit',true),
			array( 'controller' => 'commissionseps', 'action' => 'edit', $commissionep['Commissionep']['id'] )
		).' </li>';

		// FIXME: le faire dans le modèle
		$reponsesComite = Set::extract( $membresepsseanceseps, '/CommissionepMembreep/reponse' );
		$reponsesComite = Set::filter( $reponsesComite );
		if( !empty( $reponsesComite ) ) {
			foreach( $reponsesComite as $i => $reponseComite ) {
				if( $reponseComite == 'nonrenseigne' ) {
					unset( $reponsesComite[$i] );
				}
			}
		}

		if( !empty( $reponsesComite ) && count( $reponsesComite ) == count( $membresepsseanceseps ) ) { // FIXME
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::ordredujour', true ),
				array( 'controller' => 'commissionseps', 'action' => 'ordredujour', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::ordredujour',true ).'</span></li>';
		}

		if( empty( $commissionep['Commissionep']['finalisee'] ) && $countDossiers > 0 && !empty($membresepsseanceseps) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::traiterep',true ),
				array( 'controller' => 'commissionseps', 'action' => 'traiterep', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traiterep',true ).'</span></li>';
		}

		if( empty( $commissionep['Commissionep']['finalisee'] ) && $countDossiers > 0 && !empty($membresepsseanceseps) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::finaliserep',true ),
				array( 'controller' => 'commissionseps', 'action' => 'finaliserep', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finaliserep',true ).'</span></li>';
		}

		// FIXME: le faire dans le modèle
		$presencesComite = Set::extract( $membresepsseanceseps, '/CommissionepMembreep/presence' );
		$presencesComite = Set::filter( $presencesComite );
		if( !empty( $commissionep['Commissionep']['finalisee'] ) && !empty( $presencesComite ) && count( $presencesComite ) == count( $membresepsseanceseps ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::impressionpv', true ),
				array( 'controller' => 'commissionseps', 'action' => 'impressionpv', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::impressionpv',true ).'</span></li>';
		}

		if( $commissionep['Commissionep']['finalisee'] == 'ep' ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::traitercg',true ),
				array( 'controller' => 'commissionseps', 'action' => 'traitercg', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traitercg',true ).'</span></li>';
		}

		if( $commissionep['Commissionep']['finalisee'] == 'ep' ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::finalisercg',true ),
				array( 'controller' => 'commissionseps', 'action' => 'finalisercg', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finalisercg',true ).'</span></li>';
		}
	?>
	</ul>

	<table>
		<tbody>
			<tr class="odd">
				<th><?php echo "Identifiant de la séance";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['identifiant'] ) ? $commissionep['Commissionep']['identifiant'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Date de la séance";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $commissionep['Commissionep']['dateseance'])) : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Nom de l'EP";?></th>
				<td><?php echo isset( $commissionep['Ep']['name'] ) ? $commissionep['Ep']['name'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Structure référente";?></th>
				<td><?php echo isset( $commissionep['Structurereferente']['lib_struc'] ) ? $commissionep['Structurereferente']['lib_struc'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Salle de la commision";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['salle'] ) ? $commissionep['Commissionep']['salle'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Observations de la commision";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['observations'] ) ? $commissionep['Commissionep']['observations'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Décision finale";?></th>
				<td><?php echo /*debug($options);*/ Set::enum( $commissionep['Commissionep']['finalisee'], $options['Commissionep']['finalisee'] );?></td>
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
						__d('Commissionep','Commissionep.edit',true),
						array( 'controller' => 'membreseps', 'action' => 'editliste', $commissionep['Commissionep']['ep_id'], $commissionep['Commissionep']['id'] ),
						( $commissionep['Commissionep']['finalisee'] == '' )
					).' </li>';

					echo '<li>'.$xhtml->presenceLink(
						__d('Commissionep','Commissionep::presence',true),
						array( 'controller' => 'membreseps', 'action' => 'editpresence', $commissionep['Commissionep']['ep_id'], $commissionep['Commissionep']['id'] ),
						( $commissionep['Commissionep']['finalisee'] == 'ep' )
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
							'CommissionepMembreep.reponse',
							'CommissionepMembreep.presence'
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
						$membreepseanceep['CommissionepMembreep']['reponsetxt'] = "";
						if (!empty($membreepseanceep['CommissionepMembreep']['reponse'])) {
							$membreepseanceep['CommissionepMembreep']['reponsetxt'] = __d('commissionep_membreep', 'ENUM::REPONSE::'.$membreepseanceep['CommissionepMembreep']['reponse'], true);
							if ($membreepseanceep['CommissionepMembreep']['reponse']=='remplacepar')
								$membreepseanceep['CommissionepMembreep']['reponsetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
						}
						echo $html->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['reponsetxt']
						);
						$membreepseanceep['CommissionepMembreep']['presencetxt'] = "";
						if (!empty($membreepseanceep['CommissionepMembreep']['presence'])) {
							$membreepseanceep['CommissionepMembreep']['presencetxt'] = __d('commissionep_membreep', 'ENUM::PRESENCE::'.$membreepseanceep['CommissionepMembreep']['presence'], true);
							if ($membreepseanceep['CommissionepMembreep']['presence']=='remplacepar')
								$membreepseanceep['CommissionepMembreep']['presencetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
						}
						echo $html->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['presencetxt']
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
					if( empty( $commissionep['Commissionep']['finalisee'] ) ) {
						echo '<li>'.$xhtml->editLink(
							'Modifier',
							array( 'controller' => 'dossierseps', 'action' => 'choose', Set::classicExtract( $commissionep, 'Commissionep.id' ) )
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
