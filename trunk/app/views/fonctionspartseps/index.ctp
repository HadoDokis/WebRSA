<h1><?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'fonctionpartep', "Fonctionspartseps::{$this->action}", true )
    );?>
</h1>

<?php
	echo $default->index(
		$fonctionspartseps,
		array(
// 			'Fonctionpartep.id',
			'Fonctionpartep.name',
		),
		array(
			'add' => array(
				'Fonctionpartep.add'
			),
			'actions' => array(
// 				'Fonctionpartep.view',
				'Fonctionpartep.edit',
				'Fonctionpartep.delete'
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