<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Partep.id',
			'Partep.qual',
			'Partep.nom',
			'Partep.prenom',
			'Partep.tel',
			'Partep.email',
			'Partep.ep_id',
			'Partep.fonctionpartep_id',
			'Partep.rolepartep',
		)
	);

	echo $default->index(
		$partseps,
		array(
			'Partep.id',
			'Partep.qual',
			'Partep.nom',
			'Partep.prenom',
			'Partep.tel',
			'Partep.email',
			'Ep.name',
			'Fonctionpartep.name',
			'Partep.rolepartep',
		),
		array(
			'add' => array(
				'Partep.add'
			),
			'actions' => array(
				'Partep.view',
				'Partep.edit',
				'Partep.delete'
			)
		)
	);
?>