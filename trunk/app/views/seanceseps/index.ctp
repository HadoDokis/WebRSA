<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'seanceep', "Seanceseps::{$this->action}", true )
        );
    ?>
</h1>

<?php

	echo $default->index(
		$seanceseps,
		array(
			'Ep.name',
			'Structurereferente.lib_struc',
			'Seanceep.dateseance' => array( 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 2, 'minYear' => date( 'Y' ) - 2 ),
			'Seanceep.reorientation' => array( 'options' => $options ),
		),
		array(
			'add' => array(
				'Seanceep.add'
			),
			'actions' => array(
// 				'Seanceep.view',
				'Seanceep.edit',
				'Seanceep.delete'
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