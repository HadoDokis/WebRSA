<h1><?php echo $this->pageTitle = __d( 'contratcomplexeep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo $default2->index(
		$contratsinsertion,
		array(
			'Contratinsertion.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'contratcomplexeep93', 'sort' => false ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
			'Structurereferente.lib_struc',
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
			'domain' => 'contratcomplexeep93',
			'labelcohorte' => 'Enregistrer'
		)
	);
?>
<?php if( !empty( $contratsinsertion) ):?>
    <?php echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );?>
<?php endif;?>