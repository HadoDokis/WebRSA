<h1><?php echo $this->pageTitle = __d( 'contratcomplexeep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo $default2->index(
		$contratsinsertion,
		array(
			'Contratinsertion.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'contratscomplexeseps93' ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Contratinsertion.dd_ci',
			'Contratinsertion.df_ci',
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Contratinsertion.id'
			),
			'paginate' => 'Contratinsertion',
			'domain' => 'contratcomplexeep93'
		)
	);

// 	debug( $contratsinsertion );
?>