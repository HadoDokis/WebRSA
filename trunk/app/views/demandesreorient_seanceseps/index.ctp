<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'DemandesreorientSeancesep.id',
			'DemandesreorientSeancesep.demandereorient_id',
			'DemandesreorientSeancesep.seanceep_id',
		)
	);

	echo $default->index(
		$demandesreorientSeanceseps,
		array(
			'DemandesreorientSeancesep.id',
			'DemandesreorientSeancesep.demandereorient_id',
			'DemandesreorientSeancesep.seanceep_id',
		),
		array(
			'add' => array(
				'DemandesreorientSeancesep.add'
			),
			'actions' => array(
				'DemandesreorientSeancesep.view',
				'DemandesreorientSeancesep.edit',
				'DemandesreorientSeancesep.delete'
			)
		)
	);
?>