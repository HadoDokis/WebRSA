<h1><?php echo $this->pageTitle = '3. Traitement de la commission du '.date('d/m/Y à H:i', strtotime($commissionep['Commissionep']['dateseance'])).' par l\'EP : "'.$commissionep['Ep']['name'].'"';
?></h1>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<br/>
<?php
	if( Configure::read( 'Cg.departement' ) == 93 ) {
		echo '<ul class="actionMenu">';
			echo '<li>'.$xhtml->link(
				__d( 'commissionep', 'Commissionseps::view', true ),
				array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ),
				array( 'class' => 'button view external' )
			).' </li>';
		echo '</ul>';
	}
?>
<div id="tabbedWrapper" class="tabs">
	<?php
		// L'allocataire passe-t'il plusieurs fois dans cette commission
		foreach( $dossiers as $thmeme => $dossiersTmp ) {
			foreach( $dossiersTmp['liste'] as $dossier ) {
				$dossiersAllocataires[$dossier['Personne']['id']][] = $dossier['Dossierep']['themeep'];
			}
		}

		echo $form->create( null, array( 'url' => Router::url( null, true ) ) );

		foreach( array_keys( $dossiers ) as $theme ) {
			$modeleDecision = Inflector::classify( 'Decision'.Inflector::underscore( $theme ) );
			$errorClass = ( !empty( $this->validationErrors[$modeleDecision] ) ? 'error' : '' );

			$file = sprintf( 'traiterep.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'" class="'.$errorClass.'"><h2 class="title '.$errorClass.'">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier à traiter pour cette thématique.</p>';
			}
			echo '</div>';
		}

		//Ajout d'une distinction entre avis et décisions pour le CG66 vs les autres
		$avisdecisions = '';
		if( Configure::read( 'Cg.departement' ) == 66 ){
			$avisdecisions = 'avis';
		}
		else{
			$avisdecisions = 'décisions';
		}

		echo '<div class="submit">';
			echo $form->submit( 'Enregistrer', array( 'div' => false ) );
			if ( $commissionep['Commissionep']['etatcommissionep'] == 'decisionep' ) {
				echo '<br/><br/>'.$form->submit( 'Valider', array( 'name' => 'Valider', 'div' => false, 'onclick' => 'return confirm( \'Êtes-vous sûr de vouloir valider les '.$avisdecisions.' ?\' );' ) );
			}
		echo '</div>';
		echo $form->end();

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