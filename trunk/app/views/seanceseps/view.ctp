<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$seanceep,
		array(
			'Seanceep.id',
			'Ep.name',
			'Structurereferente.id',
			'Seanceep.dateseance',
			'Seanceep.finaliseeep',
			'Seanceep.finaliseecg',
			'Seanceep.reorientation',
		)
	);
?>