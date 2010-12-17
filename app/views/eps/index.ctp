<h1><?php echo $this->pageTitle = 'Liste des équipes pluridisciplinaires';?></h1>

<?php
	$fields = array(
		'Ep.identifiant',
		'Regroupementep.name',
		'Ep.name'
	);

	foreach( $themes as $theme ) {
		$fields["Ep.{$theme}"] = array( 'type' => 'text' );
	}

	echo $default2->index(
		$eps,
		$fields,
		array(
			'actions' => array(
				'Eps::edit',
				'Eps::delete'
			),
			'add' => array( 'Ep.add' ),
			'options' => $options
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
