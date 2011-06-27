<h1><?php echo $this->pageTitle = 'Commission du '.date('d-m-Y à h:i', strtotime($cov58['Cov58']['datecommission'])).' de la COV '; ?></h1>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
		foreach( array_keys( $dossiers ) as $theme ) {
			echo '<div id="'.$theme.'"><h2 class="title">'.__d( 'dossiercov58', 'ENUM::THEMECOV::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( Inflector::tableize( $theme ).'.ctp' );
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
		        'controller' => 'covs58',
		        'action'     => 'view',
		        $cov58_id
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
