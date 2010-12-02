<h1><?php echo $this->pageTitle = 'Liste des fonctions des membres des E.P.';?></h1>

<?php
	echo $default2->index(
		$regroupementeps,
		array(
// 			'Regroupementep.id',
			'Regroupementep.name'		
		),
		array(
			'actions' => array(
				'Regroupementseps::edit',
				'Regroupementseps::delete'
			),
			'add' => array( 'Regroupementseps.add' ),
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