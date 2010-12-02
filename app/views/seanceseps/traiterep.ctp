<h1><?php echo $this->pageTitle = 'Traitement de la séance par l\'EP'; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>

<?php
	foreach( array_keys( $dossiers ) as $theme ) {
		$file = sprintf( 'traiterep.%s.liste.ctp', Inflector::underscore( $theme ) );
		require_once( $file );
	}
?>