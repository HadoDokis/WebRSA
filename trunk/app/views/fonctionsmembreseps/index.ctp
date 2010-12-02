<h1><?php echo $this->pageTitle = 'Liste des fonctions des membres des E.P.';?></h1>

<?php
	echo $default2->index(
		$fonctionmembreeps,
		array(
// 			'Fonctionmembreep.id',
			'Fonctionmembreep.name'		
		),
		array(
			'actions' => array(
				'Fonctionsmembreseps::edit',
				'Fonctionsmembreseps::delete'
			),
			'add' => array( 'Fonctionsmembreseps.add' ),
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