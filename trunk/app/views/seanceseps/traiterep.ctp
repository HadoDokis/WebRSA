<h1><?php echo $this->pageTitle = 'Traitement de la séance du '.date('d-m-Y à h:i', strtotime($seanceep['Seanceep']['dateseance'])).' par l\'EP '; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		echo $form->create( null, array( 'url' => Router::url( null, true ) ) );

		foreach( array_keys( $dossiers ) as $theme ) {
			$file = sprintf( 'traiterep.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'"><h2 class="title">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier à traiter pour cette thématique.</p>';
			}
			echo '</div>';
		}

		echo $form->submit( 'Enregistrer' );
		echo $form->end();

		echo $default->button(
		    'back',
		    array(
		        'controller' => 'seanceseps',
		        'action'     => 'view',
		        $seanceep_id
		    ),
		    array(
		        'id' => 'Back'
		    )
		);
		
	?>
</div>

<?php
	//if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	//}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
