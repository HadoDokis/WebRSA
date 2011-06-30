<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'statutpdo', "Statutspdos::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$statutspdos,
		array(
			'Statutpdo.libelle'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Statutspdos::edit',
				'Statutspdos::delete',
			),
			'add' => 'Statutspdos::add',
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
