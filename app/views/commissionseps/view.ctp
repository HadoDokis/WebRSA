<h1><?php	echo $this->pageTitle = '2. Affichage de la commission d\'EP : "'.$commissionep['Ep']['name'].'"'; ?></h1>
<div  id="ficheCI">

	<?php
		if ( $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) {
			echo "<p class='error'>Quorum non atteint, la commission ne peut avoir lieu.</p>";
			if ( isset( $messageQuorum ) && !empty( $messageQuorum ) ) {
				echo "<p class='error'>{$messageQuorum}</p>";
			}
		}
	?>

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

	?>
	</ul>

	<table>
		<tbody>
			<tr class="odd">
				<th><?php echo "Identifiant de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['identifiant'] ) ? $commissionep['Commissionep']['identifiant'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Nom de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['name'] ) ? $commissionep['Commissionep']['name'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Date de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['dateseance'] ) ? strftime( '%d/%m/%Y %H:%M', strtotime( $commissionep['Commissionep']['dateseance'])) : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Nom de l'EP";?></th>
				<td><?php echo isset( $commissionep['Ep']['name'] ) ? $commissionep['Ep']['name'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "Identifiant de l'EP";?></th>
				<td><?php echo isset( $commissionep['Ep']['identifiant'] ) ? $commissionep['Ep']['identifiant'] : null ;?></td>
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
			<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
				<tr class="even">
					<th><?php echo "Chargé(e) de suivi de la commission";?></th>
					<td><?php echo isset( $commissionep['Commissionep']['chargesuivi'] ) ? $commissionep['Commissionep']['chargesuivi'] : null ;?></td>
				</tr>
				<tr class="odd">
					<th><?php echo "Gestionnaire BAT de la commission";?></th>
					<td><?php echo isset( $commissionep['Commissionep']['gestionnairebat'] ) ? $commissionep['Commissionep']['gestionnairebat'] : null ;?></td>
				</tr>
				<tr class="even">
					<th><?php echo "Gestionnaire BADA de la commission";?></th>
					<td><?php echo isset( $commissionep['Commissionep']['gestionnairebada'] ) ? $commissionep['Commissionep']['gestionnairebada'] : null ;?></td>
				</tr>
			<?php endif;?>
			<tr class="odd">
				<th><?php echo "Salle de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['salle'] ) ? $commissionep['Commissionep']['salle'] : null ;?></td>
			</tr>
			<tr class="even">
				<th><?php echo "Observations de la commission";?></th>
				<td><?php echo isset( $commissionep['Commissionep']['observations'] ) ? $commissionep['Commissionep']['observations'] : null ;?></td>
			</tr>
			<tr class="odd">
				<th><?php echo "État de la commission";?></th>
				<td><?php echo Set::enum( $commissionep['Commissionep']['etatcommissionep'], $options['Commissionep']['etatcommissionep'] );?></td>
			</tr>
			<?php if( $commissionep['Commissionep']['etatcommissionep'] == 'annule' ):?>
			<tr class="even">
				<th><?php echo "Raison de l'annulation";?></th>
				<td><?php echo h( $commissionep['Commissionep']['raisonannulation'] );?></td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
<br />
<div id="tabbedWrapper" class="tabs">
	<?php if( isset( $membresepsseanceseps ) ):?>
		<div id="participants">
			<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
				<h2 class="title">2. Gestion des participants</h2>
			<?php else:?>
				<h2 class="title">Gestion des participants</h2>
			<?php endif;?>
			<div>
				<ul class="actionMenu">
				<?php
					$disableOrdredujour = !in_array( 'commissionseps::printOrdreDuJour', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] );

					if( Configure::read( 'Cg.departement' ) == 66 ) {
						echo '<li>'.$xhtml->link(
							__d('commissionep','Commissionseps::printConvocationsParticipants',true),
							array( 'controller' => 'commissionseps', 'action' => 'printConvocationsParticipants', $commissionep['Ep']['id'], $commissionep['Commissionep']['id'] ),
							array( 'class' => 'button print', 'enabled' => ( in_array( 'membreseps::printConvocationsParticipants', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ), ),
							'Etes-vous sûr de vouloir imprimer les invitations ?'
						).' </li>';
						echo '<li>'.$xhtml->reponseLink(
							__d('commissionep','Commissionep.reponse',true),
							array( 'controller' => 'membreseps', 'action' => 'editliste', $commissionep['Commissionep']['id'] ),
							in_array( 'membreseps::editliste', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
						).' </li>';
						echo '<li>'.$xhtml->link(
							__d('commissionep','Commissionseps::printOrdresDuJour',true),
							array( 'controller' => 'commissionseps', 'action' => 'printOrdresDuJour', $commissionep['Commissionep']['id'] ),
							array( 'class' => 'button print', 'enabled' => !$disableOrdredujour ),
							'Etes-vous sûr de vouloir imprimer l\'ordre du jour ?'
						).' </li>';
					}
					else{
						echo '<li>'.$xhtml->reponseLink(
							__d('commissionep','Commissionep.reponse',true),
							array( 'controller' => 'membreseps', 'action' => 'editliste', $commissionep['Commissionep']['id'] ),
							in_array( 'membreseps::editliste', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
						).' </li>';
						echo '<li>'.$xhtml->link(
							__d('commissionep','Commissionseps::printConvocationsParticipants',true),
							array( 'controller' => 'commissionseps', 'action' => 'printConvocationsParticipants', $commissionep['Ep']['id'], $commissionep['Commissionep']['id'] ),
							array( 'class' => 'button print', 'enabled' => ( in_array( 'membreseps::printConvocationsParticipants', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ), ),
							'Etes-vous sûr de vouloir imprimer les invitations ?'
						).' </li>';
						echo '<li>'.$xhtml->link(
							__d('commissionep','Commissionseps::printOrdresDuJour',true),
							array( 'controller' => 'commissionseps', 'action' => 'printOrdresDuJour', $commissionep['Commissionep']['id'] ),
							array( 'class' => 'button print', 'enabled' => !$disableOrdredujour ),
							'Etes-vous sûr de vouloir imprimer l\'ordre du jour ?'
						).' </li>';
					}

					if( Configure::read( 'Cg.departement' ) != 93 ) {
						echo '<li>'.$xhtml->presenceLink(
							__d('commissionep','Commissionep::presence',true),
							array( 'controller' => 'membreseps', 'action' => 'editpresence', $commissionep['Commissionep']['id'] ),
							(
								in_array( 'membreseps::editpresence', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] )
								&& $presencesPossible
							)
						).' </li>';
					}
				?>
				</ul>
			<?php
				echo "<table>";
				echo $default->thead(
						array(
							'Membreep.Fonctionmembreep.name',
							'Membreep.nom',
							'Membreep.organisme',
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
							$membreepseanceep['Membreep']['organisme']
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
									'enabled' => ( in_array( 'membreseps::printConvocationParticipant', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) && ( $membreepseanceep['CommissionepMembreep']['reponse']  != 'decline' ) ),
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
									'enabled' => ( ( $membreepseanceep['CommissionepMembreep']['reponse'] == 'remplacepar' || $membreepseanceep['CommissionepMembreep']['reponse'] == 'confirme' ) && empty( $disableOrdredujour ) ),
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
			<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
				<h2 class="title">1. Liste des dossiers</h2>
			<?php else:?>
				<h2 class="title">Liste des dossiers</h2>
			<?php endif;?>
			<?php
				list( $jourCommission, $heureCommission ) = explode( ' ', $commissionep['Commissionep']['dateseance'] );

				$disableConvocationBeneficiaire = in_array( 'commissionseps::printConvocationBeneficiaire', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] );
			?>
			<div id="dossierseps">
				<?php
					$dossiersAllocataires = array();
					// L'allocataire passe-t'il plusieurs fois dans cette commission
					if( in_array( Configure::read( 'Cg.departement' ), array( 58, 93 ) ) ) {
						foreach( $dossierseps as $dossiersep ) {
							$dossiersAllocataires[$dossiersep['Dossierep']['personne_id']][] = $dossiersep['Dossierep']['themeep'];
						}
					}
					// CG 66
					else {
						foreach( $dossiers as $tmpDossiers ) {
							foreach( $tmpDossiers as $tmpDossier ) {
								$dossiersAllocataires[$tmpDossier['Personne']['id']][] = $tmpDossier['Dossierep']['themeep'];
							}
						}
					}

					$trClass = array(
						'eval' => 'count($dossiersAllocataires[#Personne.id#]) > 1 ? "multipleDossiers" : null',
						'params' => array( 'dossiersAllocataires' => $dossiersAllocataires )
					);

					foreach( $themes as $theme ) {
						require_once( "view.{$theme}.liste.ctp" );
					}

					if( in_array( Configure::read( 'Cg.departement' ), array( 58, 93 ) ) ) {
						echo "<div id=\"synthese\"><h3 class=\"title\">Synthèse</h3>";
							if( isset($dossierseps) ) {
								echo '<ul class="actions">';
								if( Configure::read( 'Cg.departement' ) == 93 ) {
									echo '<li>'.$xhtml->link(
										'Impression des fiches synthétiques',
										array( 'controller' => 'commissionseps', 'action' => 'fichessynthese', $commissionep['Commissionep']['id'], true ),
										array( 'class' => 'button fichessynthese' ),
                                        'Etes-vous sûr de vouloir imprimer les fiches synthétiques ?'
									).'</li>';
								}
								echo '<li>'.$xhtml->link(
									'Impression des convocations',
									array( 'controller' => 'commissionseps', 'action' => 'printConvocationsBeneficiaires', $commissionep['Commissionep']['id'] ),
									array( 'class' => 'button printConvocationsBeneficiaires', 'enabled' => in_array( 'commissionseps::printConvocationsBeneficiaires', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ),
                                    'Etes-vous sûr de vouloir imprimer les convocations ?'
								).'</li>';
								echo '</ul>';

								$actions = array(
									'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'historiqueseps', 'action' => 'index', '#Dossierep.Personne.id#' ), 'class' => 'external' ),
								);

// 								$fields = array(
// 									'Dossierep.Personne.qual',
// 									'Dossierep.Personne.nom',
// 									'Dossierep.Personne.prenom'
// 								);
//
// 								if( Configure::read( 'Cg.departement' ) == 93 ) {
// 									$actions['Dossierseps::fichesynthese'] = array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'fichesynthese',  Set::classicExtract( $commissionep, 'Commissionep.id' ), '#Dossierep.id#', true ) );
// 									$fields = array_merge(
// 										$fields,
// 										array(
// 											'Dossierep.Personne.id'
// 										)
// 									);
// 								}
//
// 								$fields = array_merge(
// 									$fields,
// 									array(
// 										'Dossierep.Personne.dtnai',
// 										'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
// 										'Dossierep.created',
// 										'Dossierep.themeep',
// 										'Passagecommissionep.etatdossierep',
// 										'Dossierep.Personne.Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' )
// 									)
// 								);


								if( Configure::read( 'Cg.departement' ) == 93 ){
									$fields = array(
										'Dossierep.Personne.qual',
										'Dossierep.Personne.nom',
										'Dossierep.Personne.prenom',
										'Dossierep.Personne.id',
										'Dossierep.Personne.dtnai',
										'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
										'Dossierep.created',
										'Dossierep.themeep',
										'Passagecommissionep.etatdossierep',
										'Dossierep.Personne.Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' )
									);

									$actions['Dossierseps::fichesynthese'] = array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'fichesynthese',  	Set::classicExtract( $commissionep, 'Commissionep.id' ), '#Dossierep.id#', true ) );
								}
								else if( Configure::read( 'Cg.departement' ) == 58 ){
									$fields = array(
										'Dossierep.Personne.qual',
										'Dossierep.Personne.nom',
										'Dossierep.Personne.prenom',
										'Dossierep.Personne.Foyer.Dossier.matricule',
										'Dossierep.Personne.id',
										'Dossierep.Personne.dtnai',
										'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
										'Dossierep.Personne.Orientstruct.0.structurereferente_id',
										'Dossierep.created',
										'Dossierep.themeep',
										'Passagecommissionep.etatdossierep',
										'Dossierep.Nonorientationproep58.Decisionpropononorientationprocov58.Passagecov58.Cov58.datecommission' => array( 'label' => 'Proposition validée par la COV le' ),
										'Dossierep.Personne.Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' )
									);
								}


								echo $default2->index(
									$dossierseps,
									$fields,
									array(
										'actions' => $actions,
										'options' => $options,
										'id' => $theme,
										'trClass' => array(
											'eval' => 'count($dossiersAllocataires[#Dossierep.personne_id#]) > 1 ? "multipleDossiers" : null',
											'params' => array( 'dossiersAllocataires' => $dossiersAllocataires )
										),
									)
								);

								if( Configure::read( 'Cg.departement' ) == 93 && $commissionep['Commissionep']['etatcommissionep'] == 'associe' ) {
									echo '<ul class="actionMenu center">';
										echo '<li>'.$xhtml->link(
											__d( 'commissionep','Commissionseps::validecommission', true ),
											array( 'controller' => 'commissionseps', 'action' => 'validecommission', $commissionep['Commissionep']['id'] ),
											array(),
                                            'Etes-vous sûr de vouloir valider la commission ?'
										).' </li>';
									echo '</ul>';
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
