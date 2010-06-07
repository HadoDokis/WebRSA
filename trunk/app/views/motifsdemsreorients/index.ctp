<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'motifdemreorient', "Motifsdemsreorients::{$this->action}", true )
        );
    ?>
</h1>

<?php

	echo $default->index(
		$motifsdemsreorients,
		array(
// 			'Motifdemreorient.id',
			'Motifdemreorient.name',
		),
		array(
			'add' => array(
				'Motifdemreorient.add'
			),
			'actions' => array(
// 				'Motifdemreorient.view',
				'Motifdemreorient.edit',
				'Motifdemreorient.delete'
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