<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->form(
		array(
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
?>