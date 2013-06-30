<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd1pdvs93/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'add' )
						|| !$add_enabled
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( 'questionnairesd1pdvs93', $message ), array( 'class' => "message {$class}" ) );
		}
	}

	echo $this->Default3->index(
		$questionnairesd1pdvs93,
		array(
			'Rendezvous.daterdv',
			'Questionnaired1pdv93.date_validation',
			'/Questionnairesd1pdvs93/view/#Questionnaired1pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'view' )
			),
			'/Questionnairesd1pdvs93/delete/#Questionnaired1pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'delete' )
			),
		)
	);
?>