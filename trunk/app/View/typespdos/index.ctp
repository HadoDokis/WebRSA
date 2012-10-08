<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typepdo', "Typespdos::{$this->action}", true )
	)
?>

<?php
	$fields = array(
		'Typepdo.libelle'
	);

	/*if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields[] = 'Typepdo.originepcg';
	}*/

	echo $default2->index(
		$typespdos,
		$fields,
		array(
			'options' => $options,
			'cohorte' => false,
			'actions' => array(
				'Typespdos::edit',
				'Typespdos::delete',
			),
			'add' => 'Typespdos::add',
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