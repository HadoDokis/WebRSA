<h1><?php echo $this->pageTitle = 'Décision du CG pour le dossier'; ?></h1>

<?php
	$file = sprintf( 'decisioncg.%s.ctp', Inflector::underscore( $themeName ) );
	require_once( $file );
?>
