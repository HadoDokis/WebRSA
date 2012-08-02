<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsortiecui66', "Motifssortiecuis66::{$this->action}", true )
	);

	echo $default2->index(
		$motifssortiecuis66,
		array(
			'Motifsortiecui66.name',
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Motifssortiecuis66::edit',
				'Motifssortiecuis66::delete',
			),
			'add' => 'Motifssortiecuis66::add'
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'cuis',
			'action'     => 'indexparams'
		),
		array(
			'id' => 'Back'
		)
	);
?>