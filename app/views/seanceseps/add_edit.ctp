<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une séance d\'EP';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une séance d\'EP';
	}
?>
</h1>

<?php
	echo $default->form(
		array(
			'Seanceep.ep_id',
			'Seanceep.dateseance' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'timeFormat' => __( 'Locale->timeFormat', true ) ), // TODO: à mettre par défaut dans Default2Helper
// 			'Seanceep.finalisee'
		),
		array(
			'options' => $options
		)
	);
?>
