<h1><?php echo $this->pageTitle = __d( 'defautinsertionep66', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo $default2->index(
		$personnes,
		array(
			'Orientstruct.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'defautinsertionep66' ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Orientstruct.date_valid',
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Orientstruct.personne_id',
				'Orientstruct.id'
			),
			'paginate' => 'Personne'
		)
	);
?>