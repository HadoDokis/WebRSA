<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'partep', "Partseps::{$this->action}", true )
    )
?>
<?php
	// http://mark-story.com/posts/view/generating-vcards-with-cakephp-using-extensions <-- ?
	// Voir VcfHelper

	echo $default->index(
		$partseps,
		array(
			'Partep.qual',
			'Partep.nom',
			'Partep.prenom',
// 			'Partep.nom_complet', //Commenté car les champs virtuels empechaient de voir les liens entre les tables
// 			'Partep.fullname' => array( 'type' => 'string' ),
			'Partep.tel' => array( 'type' => 'phone' ),
			'Partep.email',
		),
		array(
			'actions' => array(
// 				'Ep.view',
				'Partep.edit',
				'Partep.delete',
			),
			'add' => 'Partep.add'
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