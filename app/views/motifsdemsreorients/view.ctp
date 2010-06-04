<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->view(
		$motifdemreorient,
		array(
			'Motifdemreorient.id',
			'Motifdemreorient.name',
		)
	);
?>