<h1><?php echo $this->pageTitle = 'Liste des membres pour les équipes pluridisciplinaires';?></h1>

<?php
	echo $default2->index(
		$membreseps,
		array(
			'Membreep.nomcomplet'=>array('type'=>'text'),
			'Fonctionmembreep.name',
			'Membreep.tel',
			'Membreep.mail',
			'Membreep.nomcompletsuppleant'=>array('type'=>'text')	
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
