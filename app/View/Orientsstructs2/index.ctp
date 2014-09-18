<?php
	$personne_id = Hash::get( $dossierMenu, 'personne_id' );
	$personne = Hash::get( (array)Hash::extract( $dossierMenu, "Foyer.Personne.{n}[id={$personne_id}]" ), 0 );

	$this->pageTitle = "Orientations de {$personne['qual']} {$personne['nom']} {$personne['prenom']}";
	echo $this->Html->tag( 'h1', $this->pageTitle );

	echo $this->element( 'ancien_dossier' );

	// Messages explicatifs
	if ( empty( $orientsstructs ) ) {
		echo '<p class="notice">Cette personne ne possède pas encore d\'orientation.</p>';
	}

	if( !empty( $reorientationseps ) ) {
		echo '<p class="notice">Impossible d\'ajouter une nouvelle orientation à ce dossier (passage en EP).</p>';
	}
	else if( !empty( $reorientationscovs ) ) {
		echo '<p class="notice">Impossible d\'ajouter une nouvelle orientation à ce dossier (passage en COV).</p>';
	}
	else if( !$ajoutPossible ) {
		echo '<p class="notice">Impossible d\'ajouter une nouvelle orientation à ce dossier (dossier ne pouvant être orienté).</p>';
	}

	if( !empty( $en_procedure_relance ) ) {
		echo '<p class="notice">Cette personne est en cours de procédure de relance.</p>';
	}

	echo $this->Default3->actions( $actions );

	if( !empty( $reorientationseps ) ) {
		echo $this->Html->tag( 'h2', 'Réorientations en cours de passage en EP' );
		echo $this->Default3->index(
			$reorientationseps,
			array(
				'Dossierep.created' => array( 'type' => 'date' ),
				'Dossierep.themeep',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Orientstruct.rgorient' => array( 'type' => 'integer', 'class' => 'number' ),
				'Passagecommissionep.etatdossierep',
				'Commissionep.dateseance',
				'Commissionep.etatcommissionep',
				'/#Actions.view_url#' => array(
					'msgid' => 'Voir',
					'title' => false,
					'class' => 'view',
					'disabled' => 'empty( "#Actions.view_enabled#" )'
				),
				'/#Actions.edit_url#' => array(
					'msgid' => 'Modifier',
					'title' => false,
					'class' => 'edit',
					'disabled' => 'empty( "#Actions.edit_enabled#" )'
				),
				'/#Actions.delete_url#' => array(
					'msgid' => 'Supprimer',
					'title' => false,
					'class' => 'delete',
					'disabled' => 'empty( "#Actions.delete_enabled#" )',
					'confirm' => 'Confirmer la suppression du dossier d\'EP ?'
				),
			),
			array(
				'paginate' => false,
				'options' => $options,
				'id' => 'TableReorientationsepsIndex'
			)
		);
	}

	if( !empty( $reorientationscovs ) ) {
		echo $this->Html->tag( 'h2', 'Réorientations en cours de passage en COV' );
		echo $this->Default3->index(
			$reorientationscovs,
			array(
				'Dossiercov58.created' => array( 'type' => 'date' ),
				'Dossiercov58.themecov58',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Orientstruct.rgorient' => array( 'type' => 'integer', 'class' => 'number' ),
				'Passagecov58.etatdossiercov',
				'Cov58.datecommission',
				'Cov58.etatcov',
				'/#Actions.view_url#' => array(
					'msgid' => 'Voir',
					'title' => false,
					'class' => 'view',
					'disabled' => 'empty( "#Actions.view_enabled#" )'
				),
				'/#Actions.edit_url#' => array(
					'msgid' => 'Modifier',
					'title' => false,
					'class' => 'edit',
					'disabled' => 'empty( "#Actions.edit_enabled#" )'
				),
				'/#Actions.delete_url#' => array(
					'msgid' => 'Supprimer',
					'title' => false,
					'class' => 'delete',
					'disabled' => 'empty( "#Actions.delete_enabled#" )',
					'confirm' => 'Confirmer la suppression du dossier de COV ?'
				),
			),
			array(
				'paginate' => false,
				'options' => $options,
				'id' => 'TableReorientationscovsIndex'
			)
		);
	}

	echo $this->Html->tag( 'h2', 'Orientations effectives' );

	$departement = Configure::read( 'Cg.departement' );
	if( $departement == 93 ) {
		if( $this->Session->read( 'Auth.User.type' ) === 'cg' ) {
			$fields = array(
				'Orientstruct.date_propo',
				'Orientstruct.date_valid',
				'Orientstruct.propo_algo' => array( 'type' => 'text' ),
				'Orientstruct.origine',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Orientstruct.rgorient' => array( 'type' => 'text' ),
				'Fichiermodule.nombre',
			);
		}
		else  {
			$fields = array(
				'Orientstruct.date_valid',
				'Orientstruct.origine',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Orientstruct.rgorient' => array( 'type' => 'text' ),
				'Fichiermodule.nombre',
			);
		}
	}
	else if( $departement == 66 ) {
		$fields = array(
			'Orientstruct.date_propo',
			'Orientstruct.date_valid',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Orientstruct.rgorient' => array( 'type' => 'text' ),
			'Fichiermodule.nombre',
		);
	}
	else if( $departement == 58 ) {
		$fields = array(
			'Orientstruct.date_propo',
			'Orientstruct.date_valid',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Orientstruct.rgorient' => array( 'type' => 'text' ),
			'Sitecov58.name',
			'Cov58.datecommission',
			'Cov58.observation',
			'Fichiermodule.nombre',
		);
	}
	else {
		$fields = array(
			'Orientstruct.date_propo',
			'Orientstruct.statut_orient',
			'Orientstruct.date_valid',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Orientstruct.rgorient' => array( 'type' => 'text' ),
			'Fichiermodule.nombre',
		);
	}

	if( $departement == 66 ) {
		$links = array(
			'/Orientsstructs2/edit/#Orientstruct.id#' => array(
				'disabled' => '!( "#Orientstruct.edit#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'edit', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/impression/#Orientstruct.id#' => array(
				'disabled' => '!( "#Orientstruct.impression#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'impression', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/impression_changement_referent/#Orientstruct.id#' => array(
				'disabled' => '!( "#Orientstruct.impression_changement_referent#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'impression', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/delete/#Orientstruct.id#' => array(
				'confirm' => true,
				'disabled' => '!( "#Orientstruct.delete#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'delete', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/filelink/#Orientstruct.id#' => array(
				'disabled' => !$this->Permissions->checkDossier( 'Orientsstructs', 'filelink', $dossierMenu )
			)
		);
	}
	else {
		$links = array(
			'/Orientsstructs2/edit/#Orientstruct.id#' => array(
				'disabled' => '!( "#Orientstruct.edit#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'edit', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/impression/#Orientstruct.id#' => array(
				'disabled' => '!( "#Orientstruct.impression#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'impression', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/delete/#Orientstruct.id#' => array(
				'confirm' => true,
				'disabled' => '!( "#Orientstruct.delete#" == "1" && "'.$this->Permissions->checkDossier( 'Orientsstructs', 'delete', $dossierMenu ).'" )'
			),
			'/Orientsstructs2/filelink/#Orientstruct.id#' => array(
				'disabled' => !$this->Permissions->checkDossier( 'Orientsstructs', 'filelink', $dossierMenu )
			)
		);
	}

	// Rendu du tableau
	echo $this->Default3->index(
		$orientsstructs,
		$fields + $links,
		array(
			'paginate' => false,
			'options' => $options
		)
	);
?>