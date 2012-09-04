<h1><?php echo $this->pageTitle = __d( 'sanctionep58', "{$this->name}::{$this->action}", true );?></h1>

<?php
	$configureConditions = Configure::read( 'Selectionradies.conditions' );
	if( !empty( $configureConditions ) ) {
		$fields = array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'sanctionep58' ),
			'Dossier.matricule',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Historiqueetatpe.etat' => array( 'domain' => 'sanctionep58' ),
			'Historiqueetatpe.code' => array( 'domain' => 'sanctionep58' ),
			'Historiqueetatpe.motif' => array( 'domain' => 'sanctionep58' ),
			'Historiqueetatpe.date',
			'Serviceinstructeur.lib_service',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc'
		);
	}
	else {
		$fields = array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'sanctionep58' ),
			'Dossier.matricule',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Historiqueetatpe.motif' => array( 'domain' => 'sanctionep58' ),
			'Historiqueetatpe.date',
			'Serviceinstructeur.lib_service',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc'
		);
	}

	echo $default2->index(
		$personnes,
		$fields,
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
					array( 'controller' => 'sanctionseps58', 'action' => 'exportcsv', 'qdRadies' )
				);
			?></li>
		</ul>
<?php
		echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );
		echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );

endif;?>