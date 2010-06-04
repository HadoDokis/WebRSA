<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$partsepsSeancesep,
		array(
			'PartsepsSeancesep.id',
			'PartsepsSeancesep.partep_id',
			'PartsepsSeancesep.seanceep_id',
			'PartsepsSeancesep.reponseinvitation',
			'PartsepsSeancesep.presence',
			'PartsepsSeancesep.remplacant_partep_id',
		)
	);
?>