<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
	);

	echo $default2->index(
		$descriptionspdos,
		array(
			'Descriptionpdo.name',
			'Descriptionpdo.modelenotification',
			'Descriptionpdo.sensibilite',
			'Descriptionpdo.decisionpcg',
			'Descriptionpdo.dateactive',
			'Descriptionpdo.nbmoisecheance'
// 			'Descriptionpdo.declencheep'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'descriptionspdos::edit',
				'descriptionspdos::delete',
			),
			'add' => 'descriptionspdos::add',
			'options' => $options
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