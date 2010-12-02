<h1><?php echo $this->pageTitle = 'Liste des membres pour les équipes pluridisciplinaires';?></h1>

<?php
	echo $default2->index(
		$membreeps,
		array(
// 			'Membreep.id',
			'Fonctionmembreep.name',
			'Ep.name',
			'Membreep.qual',
			'Membreep.nom',
			'Membreep.prenom'		
		),
		array(
			'actions' => array(
				'Membreseps::edit',
				'Membreseps::delete'
			),
			'add' => array( 'Membreep.add' ),
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