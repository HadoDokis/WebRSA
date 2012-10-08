<h1><?php echo $this->pageTitle = h( __d( 'regroupementep', 'Regroupementep::index', true ) );?></h1>

<?php
	$fields = array(
		'Regroupementep.name'
	);

	if ( Configure::read( 'Cg.departement' ) != 93 ) {
		foreach( $themes as $theme ) {
			$fields[] = "Regroupementep.{$theme}";
		}
	}

	if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields[] = "Regroupementep.nbminmembre";
		$fields[] = "Regroupementep.nbmaxmembre";
	}

	echo $default2->index(
		$regroupementeps,
		$fields,
		array(
			'actions' => array(
				'Regroupementseps::edit',
				'Regroupementseps::delete'
			),
			'add' => array( 'Regroupementseps.add' ),
			'options' => $options
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'gestionseps',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>