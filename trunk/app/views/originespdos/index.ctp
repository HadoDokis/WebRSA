<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'originepdo', "Originespdos::{$this->action}", true )
	)
?>

<?php
	$fields = array(
		'Originepdo.libelle'
	);

	/*if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields[] = 'Originepdo.originepcg';
	}*/

	echo $default2->index(
		$originespdos,
		$fields,
		array(
			'options' => $options,
			'cohorte' => false,
			'actions' => array(
				'Originespdos::edit',
				'Originespdos::delete',
			),
			'add' => 'Originespdos::add',
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
