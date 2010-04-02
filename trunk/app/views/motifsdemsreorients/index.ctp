<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'motifdemreorient', "Motifsdemsreorients::{$this->action}", true )
    )
?>
<?php
	echo $default->index(
		$motifsdemsreorients,
		array(
			'Motifdemreorient.name',
		),
		array(
			'actions' => array(
// 				'Ep.view',
				'Motifdemreorient.edit',
				'Motifdemreorient.delete',
			),
			'add' => 'Motifdemreorient.add'
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