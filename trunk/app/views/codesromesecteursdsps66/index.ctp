<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'coderomesecteurdsp66', "Codesromesecteursdsps66::{$this->action}", true )
	);

	echo $default->index(
		$codesromesecteursdsps66,
		array(
			'Coderomesecteurdsp66.code',
			'Coderomesecteurdsp66.name'
		),
		array(
			'cohorte' => false,
			'actions' => array(
				'Coderomesecteurdsp66.edit',
				'Coderomesecteurdsp66.delete',
			),
			'add' => 'Coderomesecteurdsp66.add',
			'options' => $options
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'gestionsdsps',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
