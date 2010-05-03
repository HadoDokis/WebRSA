<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'rolepartep', "Rolespartseps::{$this->action}", true )
    )
?>
<?php
	echo $default->index(
		$rolespartseps,
		array(
			'Rolepartep.name',
		),
		array(
			'actions' => array(
// 				'Ep.view',
				'Rolepartep.edit',
				'Rolepartep.delete',
			),
			'add' => 'Rolepartep.add'
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