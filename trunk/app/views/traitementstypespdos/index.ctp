<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'traitementtypepdo', "Traitementstypespdos::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$traitementstypespdos,
		array(
			'Traitementtypepdo.name'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Traitementstypespdos::edit',
				'Traitementstypespdos::delete',
			),
			'add' => 'Traitementstypespdos::add',
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
