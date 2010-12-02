<h1><?php echo $this->pageTitle = 'Traitement de la séance par le CG'; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>

<?php
	foreach( array_keys( $dossiers ) as $theme ) {
		$file = sprintf( 'traitercg.%s.liste.ctp', Inflector::underscore( $theme ) );
		require_once( $file );
	}
?>