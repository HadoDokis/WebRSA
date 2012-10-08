<?php
?>
<div id="tabbedWrapper" class="tabs">
	<div id="dossiers">
	<h1 class="title" class="aere">
		<?php
			echo $this->pageTitle = sprintf(
				'Dossiers à passer dans la commission de l\'EP « %s » du %s',
				$commissionep['Ep']['name'],
				$this->Locale->date( 'Locale->datetime', $commissionep['Commissionep']['dateseance'] )
			);
		?>
	</h1>
	<br />

	<div id="dossierseps">
		<?php
			if ( isset( $themeEmpty ) && $themeEmpty == true ) {
				echo '<p class="notice">Veuillez attribuer des thèmes à l\'EP gérant la commission avant.</p>';
			}
			else {
				$dossiersAllocataires = array();
				// L'allocataire passe-t'il plusieurs fois dans cette commission
				foreach( $dossiers as $thmeme => $dossiersTmp ) {
					foreach( $dossiersTmp as $dossier ) {
						$dossiersAllocataires[$dossier['Personne']['id']][] = $dossier['Dossierep']['themeep'];
					}
				}
				$trClass = array(
					'eval' => 'count($dossiersAllocataires[#Personne.id#]) > 1 ? "multipleDossiers" : null',
					'params' => array( 'dossiersAllocataires' => $dossiersAllocataires )
				);

				foreach( $themesChoose as $theme ){
					echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ) )."</h3>";
					require_once( "choose.{$theme}.liste.ctp" );
					if( !empty( $dossiers[$theme]) ) {
						echo $this->Form->button( 'Tout cocher', array( 'onclick' => "toutCocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
						echo $this->Form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( '#{$theme} input[type=\"checkbox\"]' )" ) );
					}
					echo "</div>";
				}
			}
		?>
		</div>
	</div>
</div>
<ul class="actionMenu">
	<li>
		<?php
			echo $this->Default->button(
				'back',
				array(
					'controller' => 'commissionseps',
					'action'     => 'view',
					$commissionep_id
				),
				array(
					'id' => 'Back'
				)
			);
		?>
	</li>
	<?php if( !empty( $dossiersAllocataires ) ):?>
		<li>
			<?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'action' => 'exportcsv', $commissionep_id )
				);
			?>
		</li>
	<?php endif;?>
</ul>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
	makeTabbed( 'dossierseps', 3 );

	$$( 'td.action a' ).each( function( elmt ) {
		$( elmt ).addClassName( 'external' );
	} );
</script>