<h1><?php echo $this->pageTitle = 'Liste des motifs des demandes de réorientation à passer en EP';?></h1>

<?php
	echo $default2->index(
		$motifsreorients,
		array(
// 			'Motifreorient.id',
			'Motifreorient.name'
		),
		array(
			'actions' => array(
				'Motifsreorients::edit',
				'Motifsreorients::delete'
			),
			'add' => array( 'Motifsreorients.add' )
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'gestionseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
