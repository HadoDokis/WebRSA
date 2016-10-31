<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}" )
	)
?>

<?php
	$fields = array(
		'Situationpdo.libelle',
		'Situationpdo.isactif'
	);

	echo $this->Default2->index(
		$situationspdos,
		$fields,
		array(
			'cohorte' => false,
			'actions' => array(
				'Situationspdos::edit',
				'Situationspdos::delete' => array( 'disabled' => '\'#Situationpdo.occurences#\'!= "0"' )
			),
			'add' => 'Situationspdos::add',
            'options' => $options
		)
	);

	echo $this->Default->button(
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
