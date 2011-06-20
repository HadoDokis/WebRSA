<h1><?php echo $this->pageTitle = '4. Décisions de la commission du '.date('d/m/Y à H:i', strtotime($commissionep['Commissionep']['dateseance'])).' par le CG '; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		foreach( array_keys( $dossiers ) as $theme ) {
			$file = sprintf( 'decisioncg.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'"><h2 class="title">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier n\'a été traité pour cette thématique.</p>';
			}
			echo '</div>';
		}

		if( Configure::read( 'Cg.departement' )  == 93 ) {
			echo "<div id=\"synthese\"><h2 class=\"title\">Synthèse</h2>";
				if( isset($syntheses) ) {
					echo '<ul class="actions">';
					echo '<li>'.$xhtml->link(
						__d( 'commissionep','Commissionseps::impressionsDecisions', true ),
						array( 'controller' => 'commissionseps', 'action' => 'impressionsDecisions', $commissionep['Commissionep']['id'] ),
						array( 'class' => 'button impressionsDecisions' )
					).' </li>';
					echo '</ul>';

					echo $default2->index(
						$syntheses,
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
								'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'historiqueseps', 'action' => 'index', '#Dossierep.Personne.id#' ), 'class' => 'external' ),
								'Commissionseps::impressionDecision' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'impressionDecision',  '#Passagecommissionep.id#' ) )
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
		}

		echo $default->button(
		    'back',
		    array(
		        'controller' => 'commissionseps',
		        'action'     => 'arbitragecg'
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