<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la COV « %s » du %s',
			$cov58['Cov58']['name'],
			$locale->date( 'Locale->datetime', $cov58['Cov58']['datecommission'] )
		);

// 		echo $form->create('Covs58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	?>
</h1>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="dossierscovs">
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