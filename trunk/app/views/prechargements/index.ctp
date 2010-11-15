<h1>Modèles initialisés</h1>

<ol>
<?php
	sort( $initialized );

	foreach( $initialized as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>