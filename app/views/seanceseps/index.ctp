<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Seanceep.id',
			'Seanceep.ep_id',
			'Seanceep.structurereferente_id',
			'Seanceep.dateseance',
			'Seanceep.finaliseeep',
			'Seanceep.finaliseecg',
			'Seanceep.reorientation',
		)
	);

	echo $default->index(
		$seanceseps,
		array(
			'Seanceep.id',
			'Ep.name',
			'Structurereferente.id',
			'Seanceep.dateseance',
			'Seanceep.finaliseeep',
			'Seanceep.finaliseecg',
			'Seanceep.reorientation',
		),
		array(
			'add' => array(
				'Seanceep.add'
			),
			'actions' => array(
				'Seanceep.view',
				'Seanceep.edit',
				'Seanceep.delete'
			)
		)
	);
?>