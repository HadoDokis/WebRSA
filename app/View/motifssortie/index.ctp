<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'motifsortie', "Motifssortie::{$this->action}", true )
	);

	echo $default2->index(
		$motifssortie,
		array(
			'Motifsortie.name',
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Motifssortie::edit',
				'Motifssortie::delete',
			),
			'add' => 'Motifssortie::add'
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'actionscandidats_personnes',
			'action'     => 'indexparams'
		),
		array(
			'id' => 'Back'
		)
	);
?>