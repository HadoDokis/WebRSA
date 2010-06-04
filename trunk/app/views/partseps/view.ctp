<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$partep,
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
		)
	);
?>