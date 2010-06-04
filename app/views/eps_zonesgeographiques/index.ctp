<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'EpsZonesgeographique.id',
			'EpsZonesgeographique.ep_id',
			'EpsZonesgeographique.zonegeographique_id',
		)
	);

	echo $default->index(
		$epsZonesgeographiques,
		array(
			'EpsZonesgeographique.id',
			'EpsZonesgeographique.ep_id',
			'EpsZonesgeographique.zonegeographique_id',
		),
		array(
			'add' => array(
				'EpsZonesgeographique.add'
			),
			'actions' => array(
				'EpsZonesgeographique.view',
				'EpsZonesgeographique.edit',
				'EpsZonesgeographique.delete'
			)
		)
	);
?>