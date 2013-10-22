<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd2pdvs93/add/{$personne['Personne']['id']}" => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd2pdvs93', 'add' )
						|| !$add_enabled
			),
		)
	);

	echo $this->Default3->index(
		$questionnairesd2pdvs93,
		array(
			'Questionnaired2pdv93.situationaccompagnement',
			'Sortieautred2pdv93.name',
			'Sortieemploid2pdv93.name',
			'Questionnaired2pdv93.modified',
			'/Questionnairesd2pdvs93/edit/#Questionnaired2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd2pdvs93', 'edit' )
			),
			'/Questionnairesd2pdvs93/delete/#Questionnaired2pdv93.id#' => array(
				'confirm' => true,
				'disabled' => !$this->Permissions->check( 'Questionnairesd2pdvs93', 'delete' )
			),
		),
        array(
            'options' => $options
        )
	);
?>