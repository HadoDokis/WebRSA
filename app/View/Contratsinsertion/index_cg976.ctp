<?php
	$personne = Hash::extract( $dossierMenu, "Foyer.Personne.{n}[id={$dossierMenu['personne_id']}]" );
	$personne = array( 'Personne' => $personne[0] );

	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Contratsinsertion/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Contratsinsertion', 'add', $dossierMenu )
			),
		)
	);

	echo $this->Default3->index(
		$contratsinsertion,
		array(
			'Contratinsertion.date_saisi_ci',
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Contratinsertion.dd_ci',
			'Contratinsertion.df_ci',
			'Contratinsertion.duree_engag' => array( 'type' => 'text' ),
			'Contratinsertion.decision_ci',
			'Contratinsertion.datedecision',
			'Fichiermodule.nb_fichiers_lies' => array( 'type' => 'integer', 'class' => 'number' ),
			'/Contratsinsertion/view/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'view', $dossierMenu ).'" )',
				'title' => false
			),
			'/Contratsinsertion/edit/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'edit', $dossierMenu ).'" )',
				'title' => false
			),
			'/Contratsinsertion/valider/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'valider', $dossierMenu ).'" )',
				'title' => false
			),
			'/Contratsinsertion/impression/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'impression', $dossierMenu ).'" )',
				'title' => false
			),
			'/Contratsinsertion/cancel/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'cancel', $dossierMenu ).'" )',
				'title' => false
			),
			'/Contratsinsertion/delete/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'delete', $dossierMenu ).'" )',
				'title' => false,
				'confirm' => true
			),
			'/Contratsinsertion/filelink/#Contratinsertion.id#' => array(
				'disabled' => '!( "'.$this->Permissions->checkDossier( 'Contratsinsertion', 'filelink', $dossierMenu ).'" )',
				'title' => false
			),
		),
		array(
			'options' => Hash::merge(
				$options,
				array(
					'Contratinsertion' => array(
						'decision_ci' => $decision_ci,
						'duree_engag' => $duree_engag,
					)
				)
			),
			'paginate' => false
		)
	);
?>