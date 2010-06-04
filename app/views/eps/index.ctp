<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Ep.id',
			'Ep.name',
		)
	);

	echo $default->index(
		$eps,
		array(
			'Ep.id',
			'Ep.name',
		),
		array(
			'add' => array(
				'Ep.add'
			),
			'actions' => array(
				'Ep.view',
				'Ep.edit',
				'Ep.delete'
			)
		)
	);
?>