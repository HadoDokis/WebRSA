<h1> <?php echo $this->pageTitle = __d( 'nonrespectsanctionep93', 'Nonrespectssanctionseps93::index_traite', true );?> </h1>
<?php
	require_once( 'index.ctp' );

	echo $default2->index(
		$nonrespectssanctionseps93,
		array(
			'Dossier.matricule',
			'Personne.nom',
			'Personne.prenom',
			'Personne.nir',
			'Nonrespectsanctionep93.origine',
			'Nonrespectsanctionep93.contratinsertion_id' => array( 'type' => 'boolean' ),
			'Contratinsertion.df_ci',
			'Orientstruct.date_valid',
			//'Dossierep.Commissionep.dateseance' => array( 'type' => 'date' ),
			'Commissionep.dateseance' => array( 'type' => 'date' ),//FIXME: 0 ?
			'Nonrespectsanctionep93.rgpassage',
			'Decisionnonrespectsanctionep93.decision',
			'Decisionnonrespectsanctionep93.montantreduction',
			'Decisionnonrespectsanctionep93.dureesursis',
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
			'Decisionreorientationep93.0.decision',
			'Decisionreorientationep93.0.Typeorient.lib_type_orient' => array( 'type' => 'text' ),
			'Decisionreorientationep93.0.Structurereferente.lib_struc' => array( 'type' => 'text' ),
			'Dossierep.Commissionep.dateseance' => array( 'type' => 'date' ),*/
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
// debug( $options );
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