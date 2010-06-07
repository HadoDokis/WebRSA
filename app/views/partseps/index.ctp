<h1>
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'partep', "Partseps::{$this->action}", true )
        );
    ?>
</h1>

<?php
// 	echo $default->search(
// 		array(
// 			'Partep.id',
// 			'Partep.qual',
// 			'Partep.nom',
// 			'Partep.prenom',
// 			'Partep.tel',
// 			'Partep.email',
// 			'Partep.ep_id',
// 			'Partep.fonctionpartep_id',
// 			'Partep.rolepartep',
// 		)
// 	);

	echo $default->index(
		$partseps,
		array(
// 			'Partep.id',
			'Partep.qual',
			'Partep.nom',
			'Partep.prenom',
			'Partep.tel',
			'Partep.email',
			'Ep.name',
			'Fonctionpartep.name',
			'Partep.rolepartep' => array( 'options' => $options ),
		),
		array(
			'add' => array(
				'Partep.add'
			),
			'actions' => array(
				'Partep.view',
				'Partep.edit',
				'Partep.delete'
			)
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'eps',
            'action'     => 'indexparams'
        ),
        array(
            'id' => 'Back'
        )
    );
?>