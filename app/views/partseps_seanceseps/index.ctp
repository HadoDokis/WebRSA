<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'partep_seanceep', "PartsepsSeanceseps::{$this->action}", true )
        );
    ?>
</h1>

<?php
// debug( Inflector::classify( 'PartsepsSeanceseps' ) );
	echo $default->index(
		$partseps_seanceseps,
		array(
			'Partep.nom_complet',
			'PartepSeanceep.seanceep_id',
			'PartepSeanceep.reponseinvitation',
			'PartepSeanceep.presence',
			'PartepSeanceep.remplacant_partep_id',
		),
		array(
			'add' => array(
				'PartepSeanceep.add'
			),
			'actions' => array(
// 				'PartepSeanceep.view',
				'PartepSeanceep.edit',
				'PartepSeanceep.delete'
			),
            'options' => $options
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