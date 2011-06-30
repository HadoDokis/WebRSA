<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$partenaires,
		array(
			'Partenaire.libstruc',
			'Partenaire.codepartenaire',
			'Partenaire.numvoie',
			'Partenaire.typevoie',
			'Partenaire.nomvoie',
			'Partenaire.compladr',
			'Partenaire.numtel',
			'Partenaire.numfax',
			'Partenaire.email',
			'Partenaire.codepostal',
			'Partenaire.ville'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Partenaires::edit',
				'Partenaires::delete',
			),
			'add' => 'Partenaires::add',
			'options' => $options
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
