<h1> <?php echo $this->pageTitle = __d( 'nonrespectsanctionep93', 'Nonrespectssanctionseps93::index_encours', true );?> </h1>
<?php
	require_once( 'index.ctp' );

	echo $default2->index(
		$nonrespectssanctionseps93,
		array(
			'Dossierep.Personne.Foyer.Dossier.matricule',
			'Dossierep.Personne.nom',
			'Dossierep.Personne.prenom',
			'Dossierep.Personne.nir',
			'Nonrespectsanctionep93.contratinsertion_id' => array( 'type' => 'boolean' ),
			'Contratinsertion.df_ci',
			'Orientstruct.date_valid',
			'Dossierep.Seanceep.dateseance' => array( 'type' => 'date' ),
			'Nonrespectsanctionep93.rgpassage',
			'Nonrespectsanctionep93.decision',
			'Nonrespectsanctionep93.montantreduction',
			'Nonrespectsanctionep93.dureesursis',
			/*'Nonrespectsanctionep93.created' => array( 'type' => 'date' ),
			// Allocataire
			'Dossierep.Personne.nom',
			'Dossierep.Personne.prenom',
			// Orientation de départ
			'Orientstruct.Typeorient.lib_type_orient',
			'Orientstruct.Structurereferente.lib_struc',
			// Orientation d'accueil
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Dossierep.etapedossierep',
			'Nvsrepreorientsr93.0.decision',
			'Nvsrepreorientsr93.0.Typeorient.lib_type_orient' => array( 'type' => 'text' ),
			'Nvsrepreorientsr93.0.Structurereferente.lib_struc' => array( 'type' => 'text' ),
			'Dossierep.Seanceep.dateseance' => array( 'type' => 'date' ),*/
		),
		array(
			/*'groupColumns' => array(
				'Dossier' => array( 1, 2 ),
				'Service référent demandeur' => array( 3, 4 ),
				'Service référent d\'accueil' => array( 5, 6, 7 ),
				'Réorientation finale' => array( 9, 10 ),
			),*/
			'paginate' => 'Nonrespectsanctionep93',
			'options' => $options
		)
	);
// debug( $nonrespectssanctionseps93 );
// 	debug( Set::flatten( $nonrespectssanctionseps93 ) );
?>
<ul class="actionMenu">
	<li><?php
		echo $xhtml->exportLink(
			'Télécharger le tableau',
			array( 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
		);
	?></li>
</ul>