<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typesujetcer93', "Typessujetscers93::{$this->action}" )
	)
?>

<?php
	$fields = array(
		'Sujetcer93.name',
		'Typesujetcer93.name'
	);
	echo $this->Default2->index(
		$typessujetscers93,
		$fields,
		array(
			'cohorte' => false,
			'actions' => array(
				'Typessujetscers93::edit',
				'Typessujetscers93::delete',
			),
			'add' => 'Typessujetscers93::add'
		)
	);
	echo '<br />';
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'cers93',
			'action'     => 'indexparams'
		),
		array(
			'id' => 'Back'
		)
	);
?>