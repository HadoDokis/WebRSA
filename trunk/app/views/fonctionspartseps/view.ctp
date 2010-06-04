<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$fonctionpartep,
		array(
			'Fonctionpartep.id',
			'Fonctionpartep.name',
		)
	);
?>