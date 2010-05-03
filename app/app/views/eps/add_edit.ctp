<?php
	echo $default->form(
		array(
			'Ep.name' => array('required' => true),
			'Ep.date' => array( 'minuteInterval'=> 5, 'hourRange' => array( 8, 19 ),'dateFormat' => 'DMY', 'timeFormat' => '24', 'required' => true, 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 5 ), // FIXME: __() + dans theme
			'Ep.localisation' => array('required' => true)
		)
	);
?>