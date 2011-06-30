<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$situationspdos,
		array(
			'Situationpdo.libelle'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Situationspdos::edit',
				'Situationspdos::delete',
			),
			'add' => 'Situationspdos::add',
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
