<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$epsZonesgeographique,
		array(
			'EpsZonesgeographique.id',
			'EpsZonesgeographique.ep_id',
			'EpsZonesgeographique.zonegeographique_id',
		)
	);
?>