<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'textareacourrierpdo', "Textareascourrierspdos::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$textareascourrierspdos,
		array(
			'Courrierpdo.name',
			'Textareacourrierpdo.nomchampodt',
			'Textareacourrierpdo.name',
			'Textareacourrierpdo.ordre'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'textareascourrierspdos::edit',
				'textareascourrierspdos::delete',
			),
			'add' => 'textareascourrierspdos::add'
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'pdos',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>