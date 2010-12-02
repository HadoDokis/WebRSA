<?php $personne_id = Set::classicExtract( $this->params, 'pass.0' ); ?>

<?php if( empty( $personne_id ) ):?>
	<h1> <?php echo $this->pageTitle = 'Écran de synthèse des bilans de parcours'; ?> </h1>
	<?php
	// 	require_once( 'index.ctp' );

		echo $default2->index(
			$bilansparcours66,
			array(
				'Bilanparcours66.created' => array( 'type' => 'date' ),
				// Personne
				'Personne.nom_complet' => array( 'type' => 'text' ),
				'Contratinsertion.Personne.Foyer.Adressefoyer.0.Adresse.locaadr' => array( 'type' => 'text' ),
				// Orientation
				'Orientstruct.date_valid',
				'Orientstruct.Typeorient.lib_type_orient',
				'Orientstruct.Structurereferente.lib_struc',
				// Contrat d'insertion
				'Contratinsertion.date_saisi_ci',
				'Contratinsertion.Structurereferente.Typeorient.lib_type_orient',
				'Contratinsertion.Structurereferente.lib_struc',
				'Bilanparcours66.saisineepparcours' => array( 'type' => 'boolean' ),
				'Saisineepbilanparcours66.Dossierep.etapedossierep'
			),
			array(
				'groupColumns' => array(
					'Orientation' => array( 1, 2, 3 ),
					'Contrat d\'insertion' => array( 4, 5, 6 ),
					'Équipe pluridisciplinaire' => array( 7, 8 ),
				),
				'paginate' => 'Bilanparcours66',
				'options' => $options
			)
		);

	// 	debug( $bilansparcours66 );
	?>
<?php else:?>
	<?php
		if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ){
			$this->pageTitle = 'Bilan de parcours de la personne';
		}
		else {
			$this->pageTitle = 'Fiche de saisine de la personne';
		}

	?>
	<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

	<div class="with_treemenu">
		<h1><?php echo $this->pageTitle;?></h1>

			<?php
// 				echo $default2->index(
// 					$bilansparcours66,
// 					array(
// 						'Bilanparcours66.created' => array( 'type' => 'date' ),
// 						// Orientation
// 						/*'Orientstruct.date_valid',
// 						'Orientstruct.Typeorient.lib_type_orient',*/
// 						'Orientstruct.Structurereferente.lib_struc',
// 						// Contrat d'insertion
// 						'Contratinsertion.date_saisi_ci',
// 						'Contratinsertion.Structurereferente.Typeorient.lib_type_orient',
// 						'Contratinsertion.Structurereferente.lib_struc',
// 						'Bilanparcours66.saisineepparcours' => array( 'type' => 'boolean' ),
// 						'Saisineepbilanparcours66.Dossierep.etapedossierep'
// 					),
// 					array(
// 						/*'groupColumns' => array(
// 							'Orientation' => array( 1, 2, 3 ),
// 							'Contrat d\'insertion' => array( 4, 5, 6 ),
// 							'Équipe pluridisciplinaire' => array( 7, 8 ),
// 						),*/
// 						'paginate' => 'Bilanparcours66',
// 						'options' => $options,
// 						'add' => true
// 					)
// 				);
				echo $default->index(
					$bilansparcours66,
					array(
						'Bilanparcours66.created' => array( 'type' => 'date' ),
						'Orientstruct.Structurereferente.lib_struc',
						'Referent.nom_complet'
					),
					array(
// 						'actions' => array(
// 							'Bilanparcours66.edit',
// 							'Bilanparcours66.delete'
// 						),
						'add' => array( 'Bilanparcours66.add' => $personne_id )
					)
				)
			?>

	</div>
	<div class="clearer"><hr /></div>
<?php endif;?>
