<h1><?php echo $this->pageTitle = __d( 'sanctionep58', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo $default2->index(
		$personnes,
		array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'sanctionep58' ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Orientstruct.date_valid',
			'Serviceinstructeur.lib_service'
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Historiqueetatpe.id'
			),
			'paginate' => 'Personne',
			'domain' => 'sanctionep58'
		)
	);
?>
<?php if( !empty( $personnes ) ):?>
		<ul class="actionMenu">
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'sanctionseps58', 'action' => 'exportcsv', 'qdNonInscrits' )
				);
			?></li>
		</ul>
<?php
		echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );
		echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );
	
endif;?>