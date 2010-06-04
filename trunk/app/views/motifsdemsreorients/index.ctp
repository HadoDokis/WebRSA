<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Motifdemreorient.id',
			'Motifdemreorient.name',
		)
	);

	echo $default->index(
		$motifsdemsreorients,
		array(
			'Motifdemreorient.id',
			'Motifdemreorient.name',
		),
		array(
			'add' => array(
				'Motifdemreorient.add'
			),
			'actions' => array(
				'Motifdemreorient.view',
				'Motifdemreorient.edit',
				'Motifdemreorient.delete'
			)
		)
	);
?>