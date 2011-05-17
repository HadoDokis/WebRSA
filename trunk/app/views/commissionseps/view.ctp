<h1><?php	echo $this->pageTitle = '1. Affichage d\'une commission d\'EP'; ?></h1>
<div  id="ficheCI">
	<ul class="actionMenu">
	<?php
		if( in_array( 'commissionseps::edit', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->editLink(
				__d('Commissionep','Commissionep.edit',true),
				array( 'controller' => 'commissionseps', 'action' => 'edit', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::edit',true ).'</span></li>';
		}

		if( in_array( 'commissionseps::delete', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->cancelLink(
				__d( 'commissionep','Commissionseps::delete', true ),
				array( 'controller' => 'commissionseps', 'action' => 'delete', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::delete',true ).'</span></li>';
		}

// 		if( in_array( 'commissionseps::ordredujour', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::ordredujour', true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'ordredujour', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::ordredujour',true ).'</span></li>';
// 		}

		if( in_array( 'commissionseps::traiterep', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::traiterep',true ),
				array( 'controller' => 'commissionseps', 'action' => 'traiterep', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traiterep',true ).'</span></li>';
		}

		if( in_array( 'commissionseps::finaliserep', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::finaliserep',true ),
				array( 'controller' => 'commissionseps', 'action' => 'finaliserep', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finaliserep',true ).'</span></li>';
		}

	?>
	</ul><ul class="actionMenu">
	<?php

		if( in_array( 'commissionseps::impressionpv', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::impressionpv', true ),
				array( 'controller' => 'commissionseps', 'action' => 'impressionpv', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::impressionpv',true ).'</span></li>';
		}

		if( in_array( 'commissionseps::traitercg', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::traitercg',true ),
				array( 'controller' => 'commissionseps', 'action' => 'traitercg', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traitercg',true ).'</span></li>';
		}

		if( in_array( 'commissionseps::finalisercg', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::finalisercg',true ),
				array( 'controller' => 'commissionseps', 'action' => 'finalisercg', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finalisercg',true ).'</span></li>';
		}

		if( in_array( 'commissionseps::printDecision', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
			echo '<li>'.$xhtml->link(
				__d( 'commissionep','Commissionseps::printDecision',true ),
				array( 'controller' => 'commissionseps', 'action' => 'printDecision', $commissionep['Commissionep']['id'] )
			).' </li>';
		}
		else {
			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::printDecision',true ).'</span></li>';
		}
	?>
	</ul>

	<table>
		<tbody>
			<tr class="odd">
				<th><?php echo "Identifiant de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['identifiant'] ) ? $commissionep['Commissionep']['identifiant'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Date de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $commissionep['Commissionep']['dateseance'])) : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Nom de l'EP";?></th>
				<td><?php echo isset( $commissionep['Ep']['name'] ) ? $commissionep['Ep']['name'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Lieu de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['lieuseance'] ) ? $commissionep['Commissionep']['lieuseance'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Adresse de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['adresseseance'] ) ? $commissionep['Commissionep']['adresseseance'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Code postal de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['codepostalseance'] ) ? $commissionep['Commissionep']['codepostalseance'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Ville de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['villeseance'] ) ? $commissionep['Commissionep']['villeseance'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Salle de la commision";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['salle'] ) ? $commissionep['Commissionep']['salle'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Observations de la commision";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['observations'] ) ? $commissionep['Commissionep']['observations'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "État de la commission";?></th>
				<td><?php echo /*debug($options);*/ Set::enum( $commissionep['Commissionep']['etatcommissionep'], $options['Commissionep']['etatcommissionep'] );?></td>
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
						__d('commissionep','Commissionep.edit',true),
						array( 'controller' => 'membreseps', 'action' => 'editliste', $commissionep['Commissionep']['ep_id'], $commissionep['Commissionep']['id'] ),
						in_array( 'membreseps::editliste', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
					).' </li>';

					echo '<li>'.$xhtml->presenceLink(
						__d('commissionep','Commissionep::presence',true),
						array( 'controller' => 'membreseps', 'action' => 'editpresence', $commissionep['Commissionep']['ep_id'], $commissionep['Commissionep']['id'] ),
						(
							in_array( 'membreseps::editpresence', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
							&& $commissionAujourdhui
						)
					).' </li>';
				?>
				</ul>
			<?php
				echo "<table>";
				echo $default->thead(
						array(
							'Membreep.Fonctionmembreep.name',
							'Membreep.nom',
							'Membreep.tel',
							'Membreep.mail',
							'CommissionepMembreep.reponse',
							'CommissionepMembreep.presence'
						),
						array(
							'actions' => array(
								'Commissionseps::printConvocationParticipant'
							)
						)
					);

				$disableConvocationParticipant = in_array( 'commissionseps::printConvocationParticipant', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] );
				echo "<tbody>";
				foreach($membresepsseanceseps as $membreepseanceep) {

					echo "<tr>";
						echo $html->tag(
							'td',
							$membreepseanceep['Fonctionmembreep']['name']
						);
						echo $html->tag(
							'td',
							implode(' ', array($membreepseanceep['Membreep']['qual'], $membreepseanceep['Membreep']['nom'], $membreepseanceep['Membreep']['prenom']))
						);
						echo $html->tag(
							'td',
							$membreepseanceep['Membreep']['tel']
						);
						echo $html->tag(
							'td',
							$membreepseanceep['Membreep']['mail']
						);

						if ( empty( $membreepseanceep['CommissionepMembreep']['reponse'] ) ) {
							$membreepseanceep['CommissionepMembreep']['reponse'] = 'nonrenseigne';
						}
						$membreepseanceep['CommissionepMembreep']['reponsetxt'] = __d('commissionep_membreep', 'ENUM::REPONSE::'.$membreepseanceep['CommissionepMembreep']['reponse'], true);
						if ($membreepseanceep['CommissionepMembreep']['reponse']=='remplacepar') {
							$membreepseanceep['CommissionepMembreep']['reponsetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
						}
						echo $html->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['reponsetxt']
						);

						$membreepseanceep['CommissionepMembreep']['presencetxt'] = "";
						if (!empty($membreepseanceep['CommissionepMembreep']['presence'])) {
							$membreepseanceep['CommissionepMembreep']['presencetxt'] = __d('commissionep_membreep', 'ENUM::PRESENCE::'.$membreepseanceep['CommissionepMembreep']['presence'], true);
							if ($membreepseanceep['CommissionepMembreep']['presence']=='remplacepar') {
								$membreepseanceep['CommissionepMembreep']['presencetxt'] .= ' '.$membreepseanceep['Membreep']['suppleant'];
							}
						}
						echo $html->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['presencetxt']
						);

						echo $html->tag(
							'td',
							$xhtml->link(
								'Ordre du jour',
								array( 'controller' => 'commissionseps', 'action' => 'printConvocationParticipant', $membreepseanceep['CommissionepMembreep']['id'] ),
								array(
									'enabled' => ( ( $membreepseanceep['CommissionepMembreep']['reponse'] == 'remplacepar' || $membreepseanceep['CommissionepMembreep']['reponse'] == 'confirme' ) && empty( $disableConvocationParticipant )  && empty( $membreepseanceep['CommissionepMembreep']['presence'] ) ),
									'class' => 'button print'
								)
							),
							array( 'class' => 'action')
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

					$disableConvocationBeneficiaire = in_array( 'commissionseps::printConvocationBeneficiaire', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] );

					if( in_array( 'dossierseps::choose', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
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
// debug($dossiers);
						if( ( $theme == 'nonorientationproep58' ) || ( $theme == 'reorientationep93' ) || ( $theme == 'nonorientationproep93' ) || ( $theme == 'regressionorientationep58' ) || ( $theme == 'sanctionep58' ) ){
							$controller = 'orientsstructs';
						}
						else if( ( $theme == 'nonrespectsanctionep93' ) || ( $theme == 'saisinepdoep66' ) ){
							$controller = 'propospdos';
						}
						else if( ( $theme == 'defautinsertionep66' ) || ( $theme == 'saisinebilanparcoursep66' ) ){
							$controller = 'bilansparcours66';
						}


						echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )."</h3>";
						if( empty( $dossiers[$theme] ) ) {
							echo '<p class="notice">Il n\'existe aucun dossier de cette thématique associé à cette commission d\'EP.</p>';
						}
						else {
							echo $default2->index(
								$dossiers[$theme],
								array(
									'Dossierep.Personne.qual',
									'Dossierep.Personne.nom',
									'Dossierep.Personne.prenom',
									'Dossierep.Personne.dtnai',
									'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
									'Dossierep.created',
									'Dossierep.themeep',
									'Passagecommissionep.etatdossierep',
								),
								array(
									'actions' => array(
										'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => $controller, 'action' => 'index', '#Dossierep.Personne.id#' ), 'class' => 'external' ),
										'Commissionseps::printConvocationBeneficiaire' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'printConvocationBeneficiaire', '#Dossierep.id#' ), 'disabled' => empty( $disableConvocationBeneficiaire ))
									),
									'options' => $options,
									'id' => $theme
								)
							);

						}
						echo "</div>";
					}


					echo "<div id=synthese><h3 class=\"title\">Synthèse</h3>";
						if( isset($dossierseps) ){
							echo $default2->index(
								$dossierseps,
								array(
									'Dossierep.Personne.qual',
									'Dossierep.Personne.nom',
									'Dossierep.Personne.prenom',
									'Dossierep.Personne.dtnai',
									'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
									'Dossierep.created',
									'Dossierep.themeep',
									'Passagecommissionep.etatdossierep',
								),
								array(
									'actions' => array(
										'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => $controller, 'action' => 'index', '#Dossierep.Personne.id#' ), 'class' => 'external' ),
										'Dossierseps::fichesynthese' => array( 'url' => array( 'controller' => 'dossierseps', 'action' => 'fichesynthese', '#Dossierep.id#' ) )
									),
									'options' => $options,
									'id' => $theme
								)
							);
						}
						else {
							echo '<p class="notice">Il n\'existe aucun dossier associé à cette commission d\'EP.</p>';
						}
					echo "</div>";
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
<script type="text/javascript">
	$$( 'td.action a' ).each( function( elmt ) {
		$( elmt ).addClassName( 'external' );
	} );
</script>
