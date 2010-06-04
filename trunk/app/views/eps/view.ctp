<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$ep,
		array(
			'Ep.id',
			'Ep.name',
		)
	);
?>