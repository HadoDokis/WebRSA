<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'contactpartenaire', "Contactspartenaires::{$this->action}", true )
	)
?>

<?php
	echo $default2->index(
		$contactspartenaires,
		array(
			'Contactpartenaire.qual',
			'Contactpartenaire.nom',
			'Contactpartenaire.prenom',
			'Contactpartenaire.numtel',
			'Contactpartenaire.numfax',
			'Contactpartenaire.email',
			'Partenaire.libstruc'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Contactspartenaires::edit',
				'Contactspartenaires::delete',
			),
			'add' => 'Contactspartenaires::add',
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
