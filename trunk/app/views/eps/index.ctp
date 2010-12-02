<h1><?php echo $this->pageTitle = 'Liste des équipes pluridisciplinaires';?></h1>

<?php
	echo $default2->index(
		$eps,
		array(
			'Ep.name',
			'Regroupementep.name',
			'Ep.'.Configure::read( 'Ep.tablesaisine' ) => array( 'type' => 'text' )
		),
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