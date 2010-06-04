<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'PartsepsSeancesep.id',
			'PartsepsSeancesep.partep_id',
			'PartsepsSeancesep.seanceep_id',
			'PartsepsSeancesep.reponseinvitation',
			'PartsepsSeancesep.presence',
			'PartsepsSeancesep.remplacant_partep_id',
		)
	);

	echo $default->index(
		$partsepsSeanceseps,
		array(
			'PartsepsSeancesep.id',
			'PartsepsSeancesep.partep_id',
			'PartsepsSeancesep.seanceep_id',
			'PartsepsSeancesep.reponseinvitation',
			'PartsepsSeancesep.presence',
			'PartsepsSeancesep.remplacant_partep_id',
		),
		array(
			'add' => array(
				'PartsepsSeancesep.add'
			),
			'actions' => array(
				'PartsepsSeancesep.view',
				'PartsepsSeancesep.edit',
				'PartsepsSeancesep.delete'
			)
		)
	);
?>