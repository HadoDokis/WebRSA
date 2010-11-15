<h1>Préchargement du cache</h1>

<h2>Modèles</h2>

<h3>Modèles initialisés (<?php echo count($initialized);?>)</h3>

<ol>
<?php
	sort( $initialized );

	foreach( $initialized as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>

<h3>Modèles non initialisés (<?php echo count($uninitialized);?>)</h3>

<ol>
<?php
	sort( $uninitialized );

	foreach( $uninitialized as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>

<h3>Tables sans modèle lié (<?php echo count($missing);?>)</h3>

<ol>
<?php
	sort( $missing );

	foreach( $missing as $model ) {
		echo '<li>'.$model.'</li>';
	}
?>
</ol>

<h2>Traductions</h2>

<ol>
<?php
	sort( $domaines );

	foreach( $domaines as $domaine ) {
		echo '<li>'.$domaine.'</li>';
	}
?>
</ol>