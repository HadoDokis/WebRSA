<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd1pdvs93/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'add' ) || in_array( '0', Hash::extract( $questionnairesd1pdvs93, '{n}.Questionnaired1pdv93.valide' ) )
			),
		)
	);

	echo $this->Default3->index(
		$questionnairesd1pdvs93,
		array(
			'Rendezvous.daterdv',
			'Questionnaired1pdv93.valide' => array( 'type' => 'boolean' ),
			'Questionnaired1pdv93.date_validation',
			'/Questionnairesd1pdvs93/view/#Questionnaired1pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'view' )
			),
			'/Questionnairesd1pdvs93/edit/#Questionnaired1pdv93.id#' => array(
				'disabled' => '( "#Questionnaired1pdv93.valide#" == "1" ) || ( "'.$this->Permissions->check( 'Questionnairesd1pdvs93', 'edit' ).'" != "1" )'
			),
			'/Questionnairesd1pdvs93/delete/#Questionnaired1pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'delete' )
			),
		)
	);
?>