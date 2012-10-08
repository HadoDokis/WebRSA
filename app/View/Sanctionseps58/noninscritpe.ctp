<h1><?php echo $this->pageTitle = __d( 'sanctionep58', "{$this->name}::{$this->action}" );?></h1>

<?php
	echo $this->Default2->index(
		$personnes,
		array(
			'Orientstruct.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'sanctionep58' ),
			'Dossier.matricule',
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
				'Orientstruct.id'
			),
			'paginate' => 'Personne',
			'domain' => 'sanctionep58'
		)
	);
?>
<?php if( !empty( $personnes ) ):?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'sanctionseps58', 'action' => 'exportcsv', 'qdNonInscrits' )
				);
			?></li>
		</ul>
<?php
		echo $this->Form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );
		echo $this->Form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );

endif;?>