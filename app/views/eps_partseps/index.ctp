<?php
	echo $default->index(
		$eps_partseps,
		array(
			'Ep.name',
			'Ep.date',
			'Partep.nom_complet',
			'Rolepartep.name',
			'EpPartep.presencepre' => array( 'type' => 'boolean' ),
			'EpPartep.presenceeff' => array( 'valueclass' => true ),
			'Parteprempl.nom_complet',
		),
		array(
			'actions' => array(
				'EpPartep.edit',
				'EpPartep.delete',
			),
			'add' => 'EpPartep.add',
			'options' => $options
		)
	);

// 	debug( $eps_partseps );
?>