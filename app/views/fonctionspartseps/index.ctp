<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Fonctionpartep.id',
			'Fonctionpartep.name',
		)
	);

	echo $default->index(
		$fonctionspartseps,
		array(
			'Fonctionpartep.id',
			'Fonctionpartep.name',
		),
		array(
			'add' => array(
				'Fonctionpartep.add'
			),
			'actions' => array(
				'Fonctionpartep.view',
				'Fonctionpartep.edit',
				'Fonctionpartep.delete'
			)
		)
	);
?>