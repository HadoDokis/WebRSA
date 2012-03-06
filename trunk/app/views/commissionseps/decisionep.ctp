<h1><?php 
    $typeAvisDecisions = 'Décisions';
    if( Configure::read( 'Cg.departement' ) == 66 ){
        $typeAvisDecisions = 'Avis';
    }
    echo $this->pageTitle = $typeAvisDecisions.' de la commission du '.date('d/m/Y à H:i', strtotime($commissionep['Commissionep']['dateseance'])).' par l\'EP : "'.$commissionep['Ep']['name'].'"';
?></h1>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		// L'allocataire passe-t'il plusieurs fois dans cette commission
		foreach( $dossiers as $thmeme => $dossiersTmp ) {
			foreach( $dossiersTmp['liste'] as $dossier ) {
				$dossiersAllocataires[$dossier['Personne']['id']][] = $dossier['Dossierep']['themeep'];
			}
		}

		foreach( array_keys( $dossiers ) as $theme ) {
			$file = sprintf( 'decisionep.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'"><h2 class="title">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier n\'a été traité pour cette thématique.</p>';
			}
			echo '</div>';
		}

		if( in_array( Configure::read( 'Cg.departement' ), array( 58, 93 ) ) ) {
			echo "<div id=\"synthese\"><h2 class=\"title\">Synthèse</h2>";
				if( isset($syntheses) ) {
					echo '<ul class="actions">';
					if( Configure::read( 'Cg.departement' )  == 93 ) {
						echo '<li>'.$xhtml->link(
							'Impression des fiches synthétiques',
							array( 'controller' => 'commissionseps', 'action' => 'fichessynthese', $commissionep['Commissionep']['id'], false ),
							array( 'class' => 'button fichessynthese' ),
                            'Etes-vous sûr de vouloir imprimer les fiches synthétiques ?'
						).'</li>';
					}
					elseif ( Configure::read( 'Cg.departement' )  == 58 ) {
						echo '<li>'.$xhtml->link(
							__d( 'commissionep','Commissionseps::impressionsDecisions', true ),
							array( 'controller' => 'commissionseps', 'action' => 'impressionsDecisions', $commissionep['Commissionep']['id'] ),
							array( 'class' => 'button impressionsDecisions', 'enabled' => ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' ) ),
                            'Etes-vous sûr de vouloir imprimer les décisions ?'
						).' </li>';
					}
					echo '<li>'.$xhtml->link(
						__d( 'commissionep','Commissionseps::impressionpv', true ),
						array( 'controller' => 'commissionseps', 'action' => 'impressionpv', $commissionep['Commissionep']['id'] ),
						array( 'class' => 'button impressionpv', 'enabled' => ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' ) ),
                        'Etes-vous sûr de vouloir imprimer le PV de la commission ?'
					).' </li>';
					echo '</ul>';

					$actions = array(
						'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'historiqueseps', 'action' => 'view_passage', '#Passagecommissionep.id#' ), 'class' => 'external' ),
					);

					if( Configure::read( 'Cg.departement' )  == 93 ) {
						$fields = array(
							'Dossierep.Personne.qual',
							'Dossierep.Personne.nom',
							'Dossierep.Personne.prenom',
							'Dossierep.Personne.dtnai',
							'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
							'Dossierep.created',
							'Dossierep.themeep',
							'Passagecommissionep.etatdossierep',
						);

						$actions['Dossierseps::fichesynthese'] = array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'fichesynthese',  Set::classicExtract( $commissionep, 'Commissionep.id' ), '#Dossierep.id#', false ) );
					}
					elseif ( Configure::read( 'Cg.departement' )  == 58 ) {
						$fields = array(
							'Dossierep.Personne.qual',
							'Dossierep.Personne.nom',
							'Dossierep.Personne.prenom',
							'Dossierep.Personne.dtnai',
							'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
							'Dossierep.created',
							'Dossierep.Nonorientationproep58.Decisionpropononorientationprocov58.Passagecov58.Cov58.datecommission' => array( 'label' => 'Proposition validée par la COV le' ),
							'Dossierep.themeep',
							'Passagecommissionep.etatdossierep',
						);
						$actions['Commissionseps::impressionDecision'] = array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'impressionDecision',  '#Passagecommissionep.id#' ), 'disabled' => ( $commissionep['Commissionep']['etatcommissionep'] == 'annule' ) );
					}

					echo $default2->index(
						$syntheses,
						$fields,
						array(
							'actions' => $actions,
							'options' => $options,
							'id' => $theme,
							'trClass' => array(
								'eval' => 'count($dossiersAllocataires[#Dossierep.personne_id#]) > 1 ? "multipleDossiers" : null',
								'params' => array( 'dossiersAllocataires' => $dossiersAllocataires )
							)
						)
					);

					if( $commissionep['Commissionep']['etatcommissionep'] == 'associe' ) {
						echo '<ul class="actionMenu center">';
							echo '<li>'.$xhtml->link(
								__d( 'commissionep','Commissionseps::validecommission', true ),
								array( 'controller' => 'commissionseps', 'action' => 'validecommission', $commissionep['Commissionep']['id'] )
							).' </li>';
						echo '</ul>';
					}
				}
				else {
					echo '<p class="notice">Il n\'existe aucun dossier associé à cette commission d\'EP.</p>';
				}
			echo "</div>";
		}
		else {
			echo '<ul class="actions center">';
				echo '<li>'.$xhtml->link(
					__d( 'commissionep','Commissionseps::impressionpv', true ),
					array( 'controller' => 'commissionseps', 'action' => 'impressionpv', $commissionep['Commissionep']['id'] ),
					array( 'class' => 'button impressionpv' ),
                    'Etes-vous sûr de vouloir imprimer le PV de la commission ?'
				).' </li>';
			echo '</ul>';
		}

		echo $default->button(
			'back',
			array(
				'controller' => 'commissionseps',
				'action'     => 'arbitrageep'
			),
			array(
				'id' => 'Back'
			)
		);

	?>
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