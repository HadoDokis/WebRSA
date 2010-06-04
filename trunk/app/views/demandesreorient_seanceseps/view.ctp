<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$demandesreorientSeancesep,
		array(
			'DemandesreorientSeancesep.id',
			'DemandesreorientSeancesep.demandereorient_id',
			'DemandesreorientSeancesep.seanceep_id',
		)
	);
?>