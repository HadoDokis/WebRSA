<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd2pdvs93/add/{$personne['Personne']['id']}" => array(
				'disabled' => (
					!$this->Permissions->check( 'Questionnairesd2pdvs93', 'add' )
					|| !$add_enabled
				)
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( 'questionnairesd2pdvs93', $message ), array( 'class' => "message {$class}" ) );
		}
	}

	echo $this->Default3->index(
		$questionnairesd2pdvs93,
		array(
			'Pdv.lib_struc',
			'Questionnaired2pdv93.modified',
			'Questionnaired2pdv93.situationaccompagnement',
			'Sortieaccompagnementd2pdv93.name',
			'Questionnaired2pdv93.chgmentsituationadmin',
			'/Questionnairesd2pdvs93/edit/#Questionnaired2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd2pdvs93', 'edit' )
			),
			'/Questionnairesd2pdvs93/delete/#Questionnaired2pdv93.id#' => array(
				'confirm' => true,
				'disabled' => !$this->Permissions->check( 'Questionnairesd2pdvs93', 'delete' )
			),
		),
        array(
            'options' => $options,
			'paginate' => false,
			'domain' => 'questionnairesd2pdvs93'
        )
	);
?>