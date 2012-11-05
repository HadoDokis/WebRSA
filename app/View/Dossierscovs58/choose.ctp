<h1>
	<?php
		echo $this->pageTitle = sprintf(
			'Dossiers à passer dans la COV « %s » du %s',
			$cov58['Cov58']['name'],
			$this->Locale->date( 'Locale->datetime', $cov58['Cov58']['datecommission'] )
		);

// 		echo $this->Form->create('Covs58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
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
					echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossiercov58',  'ENUM::THEMECOV::'.Inflector::tableize( $theme ) )."</h3>";
					require_once( "choose.{$class}.liste.ctp" );
					if( !empty( $dossiers[$theme]) ) {
						echo $this->Form->button( 'Tout cocher', array( 'onclick' => "toutCocher( '#{$theme} input[type=checkbox]' );return false;" ) );
						echo $this->Form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( '#{$theme} input[type=checkbox]' );return false;" ) );
					}
					echo "</div>";
				}
			}
		?>
	</div>
</div>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 3 );
</script>