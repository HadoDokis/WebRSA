<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->form(
		array(
			'Seanceep.ep_id',
			'Seanceep.structurereferente_id',
			'Seanceep.dateseance',
			'Seanceep.finaliseeep',
			'Seanceep.finaliseecg',
			'Seanceep.reorientation',
		)
	);
?>