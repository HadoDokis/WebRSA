<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd2pdvs93/add/{$personne['Personne']['id']}" => array(
				'disabled' => (
					!$this->Permissions->checkDossier( 'Questionnairesd2pdvs93', 'add', $dossierMenu )
					|| !$add_enabled
				)
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	echo $this->Default3->messages( $messages );

	echo $this->Default3->index(
		$questionnairesd2pdvs93,
		array(
			'Structurereferente.lib_struc',
			'Questionnaired2pdv93.date_validation',
			'Questionnaired2pdv93.modified',
			'Questionnaired2pdv93.situationaccompagnement',
			'Sortieaccompagnementd2pdv93.name',
			'Questionnaired2pdv93.chgmentsituationadmin',
			'/Questionnairesd2pdvs93/edit/#Questionnaired2pdv93.id#' => array(
				'disabled' => '!'.WebrsaPermissions::checkD1D2(
					'#Structurereferente.id#',
					$this->Permissions->check( 'Questionnairesd2pdvs93', 'edit' ),
					true
				)
			),
			'/Questionnairesd2pdvs93/delete/#Questionnaired2pdv93.id#' => array(
				'confirm' => true,
				'disabled' => '!'.WebrsaPermissions::checkD1D2(
					'#Structurereferente.id#',
					$this->Permissions->check( 'Questionnairesd2pdvs93', 'delete' ),
					true
				)
			),
		),
        array(
            'options' => $options,
			'paginate' => false,
			'domain' => 'questionnairesd2pdvs93'
        )
	);
?>