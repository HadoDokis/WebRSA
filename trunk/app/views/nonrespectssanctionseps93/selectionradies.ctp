<h1><?php echo $this->pageTitle = __d( 'nonrespectsanctionep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
// debug($personnes);
	echo $default2->index(
		$personnes,
		array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'nonrespectsanctionep93', 'sort' => false ),
			'Personne.nom' => array( 'sort' => false ),
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Historiqueetatpe.date',
			'Typeorient.lib_type_orient',
			'Contratinsertion.present' => array( 'type' => 'boolean' )
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Historiqueetatpe.id'
			),
			'paginate' => 'Personne',
			'domain' => 'nonrespectsanctionep93',
			'labelcohorte' => 'Enregistrer'
		)
	);
?>
<?php if( !empty( $personnes ) ):?>
    <?php echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );?>
<?php endif;?>