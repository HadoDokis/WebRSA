<?php
	echo $theme->form(
		array(
			'Ep.name',
			'Ep.date',
			'Ep.terminee' => array( 'type' => 'checkbox' ),
		)
	);
?>