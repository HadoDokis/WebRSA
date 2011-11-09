<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la COV « %s » du %s',
			$cov58['Cov58']['name'],
			$locale->date( 'Locale->datetime', $cov58['Cov58']['datecommission'] )
		);

		echo $form->create('Covs58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	?>
</h1>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="dossierscovs">
		<?php

// 			foreach( $themesChoose as $theme ) {
// 				if( $theme == 'propoorientationcov58' ){
// 					$controller = 'orientsstructs';
// 				}
// 				else if( $theme == 'propocontratinsertioncov58' ){
// 					$controller = 'contratsinsertion';
// 				}
// 				else if( $theme == 'propononorientationprocov58' ){
// 					$controller = 'orientsstructs';
// 				}
// // debug($theme);
// // debug($dossiers);
// // 				$class = Inflector::classify( $theme );
// 				echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossiercov58',  'ENUM::THEMECOV::'.$theme, true )."</h3>";
// 					if (empty($dossiers[$theme])) {
// 						echo "Il n'y a aucun dossier en attente pour ce thème";
// 					}
// 					else {
// 						echo "<table><thead><tr>";
// 							echo $xhtml->tag( 'th', __d( 'dossiercov58', 'Dossiercov58.chosen', true ) );
// 							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.qual', true ) );
// 							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.nom', true ) );
// 							echo $xhtml->tag( 'th', __d( 'personne', 'Personne.prenom', true ) );
// 							echo $xhtml->tag( 'th', 'Action' );
// 						echo "</tr></thead><tbody>";
// 						foreach($dossiers[$theme] as $key => $dossiercov) {
// // debug( $dossiers[$theme] );
// 							echo "<tr>";
// 								echo $form->input( $theme.'.'.$key.'.id', array( 'type' => 'hidden', 'value' => $dossiercov['Dossiercov58']['id'] ) );
// 								echo $xhtml->tag( 'td', $form->input( $theme.'.'.$key.'.chosen', array( 'type' => 'checkbox', 'label' => false, 'checked' => ( $dossiercov['Passagecov58']['chosen'] == 1 ) ? 'checked' : false ) ) );
// 								echo $xhtml->tag( 'td', $dossiercov['Personne']['qual'] );
// 								echo $xhtml->tag( 'td', $dossiercov['Personne']['nom'] );
// 								echo $xhtml->tag( 'td', $dossiercov['Personne']['prenom'] );
// 								echo $xhtml->tag( 'td', $xhtml->link( 'Voir', array( 'controller' => $controller, 'action' => 'index',  $dossiercov['Personne']['id'] ), array( 'class' => 'external' ) ) );
// 							echo "</tr>";
// 						}
// 						echo "</tbody></table>";
// 					}
// 					if( !empty( $dossiercov[$theme]) ) {
// 						echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
// 						echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
// 					}
// 				echo "</div>";
// 			}
		?>
		<?php
			if ( isset( $themeEmpty ) && $themeEmpty == true ) {
				echo '<p class="notice">Veuillez attribuer des thèmes à l\'EP gérant la commission avant.</p>';
			}
			else {
				$dossiersAllocataires = array();
				// L'allocataire passe-t'il plusieurs fois dans cette commission
				foreach( $dossiers as $thmeme => $dossiersTmp ) {
					foreach( $dossiersTmp as $dossier ) {
						$dossiersAllocataires[$dossier['Personne']['id']][] = $dossier['Dossiercov58']['themecov58'];
					}
				}
				$trClass = array(
					'eval' => 'count($dossiersAllocataires[#Personne.id#]) > 1 ? "multipleDossiers" : null',
					'params' => array( 'dossiersAllocataires' => $dossiersAllocataires )
				);

				foreach( $themesChoose as $theme ){
					$class = Inflector::singularize( $theme );
					echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossiercov58',  'ENUM::THEMECOV::'.Inflector::tableize( $theme ), true )."</h3>";
					require_once( "choose.{$class}.liste.ctp" );
					if( !empty( $dossiers[$theme]) ) {
						echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
						echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
					}
					echo "</div>";
				}
			}
		?>
	</div>
</div>
<?php
// 	echo $form->end('Valider');
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}

	echo $default->button(
		'back',
		array(
			'controller' => 'covs58',
			'action'     => 'view',
			$cov58_id
		),
		array(
			'id' => 'Back'
		)
	);
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 3 );
</script>