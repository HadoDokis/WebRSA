<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true )
    )
?>
<?php
	/// FIXME: voir DefaultHelper ligne 320
	echo $default->index(
		$eps,
		array(
			'Ep.name',
			'Ep.date',
			'Ep.localisation',
// 			'Ep.nbrdemandesreorient',
// 			'Ep.nbrparcoursdetectes',
		),
		array(
			'actions' => array(
				'Ep.edit',
				'Ep.delete',
			),
			'add' => 'Ep.add'
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