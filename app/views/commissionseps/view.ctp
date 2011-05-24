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


// 		if( in_array( 'commissionseps::traiterep', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::traiterep',true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'traiterep', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traiterep',true ).'</span></li>';
// 		}
// 
// 		if( in_array( 'commissionseps::finaliserep', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::finaliserep',true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'finaliserep', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finaliserep',true ).'</span></li>';
// 		}

	?>
	</ul><!--<ul class="actionMenu">-->
	<?php

// 		if( in_array( 'commissionseps::impressionpv', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::impressionpv', true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'impressionpv', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::impressionpv',true ).'</span></li>';
// 		}
// 
// 		if( in_array( 'commissionseps::traitercg', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::traitercg',true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'traitercg', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::traitercg',true ).'</span></li>';
// 		}
// 
// 		if( in_array( 'commissionseps::finalisercg', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::finalisercg',true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'finalisercg', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::finalisercg',true ).'</span></li>';
// 		}
// 
// 		if( in_array( 'commissionseps::printDecision', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
// 			echo '<li>'.$xhtml->link(
// 				__d( 'commissionep','Commissionseps::printDecision',true ),
// 				array( 'controller' => 'commissionseps', 'action' => 'printDecision', $commissionep['Commissionep']['id'] )
// 			).' </li>';
// 		}
// 		else {
// 			echo '<li><span class="disabled"> '.__d( 'commissionep','Commissionseps::printDecision',true ).'</span></li>';
// 		}
	?>
<!-- 	</ul> -->

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
			<h2 class="title">2. Gestion des participants</h2>
			<div>
				<ul class="actionMenu">
				<?php
					echo '<li>'.$xhtml->reponseLink(
						__d('commissionep','Commissionep.reponse',true),
						array( 'controller' => 'membreseps', 'action' => 'editliste', $commissionep['Commissionep']['id'] ),
						in_array( 'membreseps::editliste', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
					).' </li>';

					echo '<li>'.$xhtml->presenceLink(
						__d('commissionep','Commissionep::presence',true),
						array( 'controller' => 'membreseps', 'action' => 'editpresence', $commissionep['Commissionep']['id'] ),
						(
							in_array( 'membreseps::editpresence', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
							&& $presencesPossible
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
								'Commissionseps::printConvocationParticipant',
								'Commissionseps::printOrdreDuJour'
							)
						)
					);

				$disableOrdredujour = !in_array( 'commissionseps::printOrdreDuJour', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] );
				echo "<tbody>";
				foreach($membresepsseanceseps as $membreepseanceep) {

					echo "<tr>";
						echo $xhtml->tag(
							'td',
							$membreepseanceep['Fonctionmembreep']['name']
						);
						echo $xhtml->tag(
							'td',
							implode(' ', array($membreepseanceep['Membreep']['qual'], $membreepseanceep['Membreep']['nom'], $membreepseanceep['Membreep']['prenom']))
						);
						echo $xhtml->tag(
							'td',
							$membreepseanceep['Membreep']['tel']
						);
						echo $xhtml->tag(
							'td',
							$membreepseanceep['Membreep']['mail']
						);

						if ( empty( $membreepseanceep['CommissionepMembreep']['reponse'] ) ) {
							$membreepseanceep['CommissionepMembreep']['reponse'] = 'nonrenseigne';
						}
						$membreepseanceep['CommissionepMembreep']['reponsetxt'] = __d('commissionep_membreep', 'ENUM::REPONSE::'.$membreepseanceep['CommissionepMembreep']['reponse'], true);
						if ($membreepseanceep['CommissionepMembreep']['reponse']=='remplacepar') {
							$membreepseanceep['CommissionepMembreep']['reponsetxt'] .= ' '.$listemembreseps[$membreepseanceep['CommissionepMembreep']['reponsesuppleant_id']];
						}
						echo $xhtml->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['reponsetxt']
						);
// debug($membreepseanceep['Membreep']);
						$membreepseanceep['CommissionepMembreep']['presencetxt'] = "";
						if (!empty($membreepseanceep['CommissionepMembreep']['presence'])) {
							$membreepseanceep['CommissionepMembreep']['presencetxt'] = __d('commissionep_membreep', 'ENUM::PRESENCE::'.$membreepseanceep['CommissionepMembreep']['presence'], true);
							if ($membreepseanceep['CommissionepMembreep']['presence']=='remplacepar') {
								$membreepseanceep['CommissionepMembreep']['presencetxt'] .= ' '.$listemembreseps[$membreepseanceep['CommissionepMembreep']['presencesuppleant_id']];
							}
						}
						echo $xhtml->tag(
							'td',
							$membreepseanceep['CommissionepMembreep']['presencetxt']
						);

						echo $xhtml->tag(
							'td',
							$xhtml->link(
								'Invitation',
								array( 'controller' => 'commissionseps', 'action' => 'printConvocationParticipant', $commissionep['Commissionep']['id'], $membreepseanceep['Membreep']['id'] ),
								array(
									'enabled' => ( $membreepseanceep['CommissionepMembreep']['reponse'] == 'nonrenseigne' /*&& empty( $disableOrdredujour )*/  && empty( $membreepseanceep['CommissionepMembreep']['presence'] ) ),
									'class' => 'button print'
								)
							),
							array( 'class' => 'action')
						);


						echo $xhtml->tag(
							'td',
							$xhtml->link(
								'Ordre du jour',
								array( 'controller' => 'commissionseps', 'action' => 'printOrdreDuJour', $membreepseanceep['CommissionepMembreep']['id'] ),
								array(
									'enabled' => ( ( $membreepseanceep['CommissionepMembreep']['reponse'] == 'remplacepar' || $membreepseanceep['CommissionepMembreep']['reponse'] == 'confirme' ) && empty( $disableOrdredujour )  && empty( $membreepseanceep['CommissionepMembreep']['presence'] ) ),
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
			<?php
				list( $jourCommission, $heureCommission ) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
				$convocationPossible = ( date( 'Y-m-d' ) < $jourCommission );
				$disableConvocationBeneficiaire = in_array( 'commissionseps::printConvocationBeneficiaire', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) && !$convocationPossible;
			?>
			<div id="dossierseps">
				<?php
					foreach( $themes as $theme ) {
						require_once( "view.{$theme}.liste.ctp" );
					}

					if( Configure::read( 'Cg.departement' )  == 93 ){
						echo "<div id=\"synthese\"><h3 class=\"title\">Synthèse</h3>";
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
											'Dossierseps::fichesynthese' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'fichesynthese',  Set::classicExtract( $commissionep, 'Commissionep.id' ), '#Dossierep.id#' ) )
										),
										'options' => $options,
										'id' => $theme
									)
								);
								if( $commissionep['Commissionep']['etatcommissionep'] == 'associe' ) {
									echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
									echo $form->submit( 'Valider', array( 'name' => 'Valider' ) );
									echo $form->end();
								}
							}
							else {
								echo '<p class="notice">Il n\'existe aucun dossier associé à cette commission d\'EP.</p>';
							}
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
<script type="text/javascript">
	$$( 'td.action a' ).each( function( elmt ) {
		$( elmt ).addClassName( 'external' );
	} );
</script>
