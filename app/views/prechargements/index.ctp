<h1>Modèles initialisés (<?php echo count($initialized);?>)</h1>

<ol>
<?php
	sort( $initialized );

	foreach( $initialized as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>

<h1>Modèles non initialisés (<?php echo count($uninitialized);?>)</h1>

<ol>
<?php
	sort( $uninitialized );

	foreach( $uninitialized as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>

<h1>Tables sans modèle lié (<?php echo count($missing);?>)</h1>

<ol>
<?php
	sort( $missing );

	foreach( $missing as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>