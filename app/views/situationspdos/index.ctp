<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
	)
?>

<?php
	$fields = array(
		'Situationpdo.libelle'
	);

// 	if ( Configure::read( 'Cg.departement' ) == 66 ) {
// 		$fields['Situationpdo.nc'] = array( 'type' => 'boolean' );
// 		$fields['Situationpdo.nr'] = array( 'type' => 'boolean' );
// 	}

	echo $default2->index(
		$situationspdos,
		$fields,
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
