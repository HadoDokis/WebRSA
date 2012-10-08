<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'sitecov58', "Sitescovs58::{$this->action}", true )
	);

	echo $default2->index(
		$sitescovs58,
		array(
			'Sitecov58.name'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Sitescovs58::edit',
				'Sitescovs58::delete',
			),
			'add' => 'Sitescovs58::add'
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>