<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true )
        );
    ?>
</h1>


<?php
// 	echo $default->search(
// 		array(
// // 			'Ep.id',
// 			'Ep.name',
// 		)
// 	);

	echo $default->index(
		$eps,
		array(
// 			'Ep.id',
			'Ep.name',
		),
		array(
			'add' => array(
				'Ep.add'
			),
			'actions' => array(
				'Ep.view',
				'Ep.edit',
				'Ep.delete'
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