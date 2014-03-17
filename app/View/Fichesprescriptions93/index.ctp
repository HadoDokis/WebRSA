<?php
	// TODO: bouton add, ...
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Fichesprescriptions93/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Fichesprescriptions93', 'add', $dossierMenu ) || !$addEnabled
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	// TODO: à factoriser avec app/View/Questionnairesd1pdvs93/index.ctp et app/View/Questionnairesd2pdvs93/index.ctp
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( $this->request->params['controller'], $message ), array( 'class' => "message {$class}" ) );
		}
	}

	echo $this->Default3->index(
		$results,
		array(
			'Ficheprescription93.created' => array( 'type' => 'date' ),
			'Thematiquefp93.type',
			'Thematiquefp93.name',
			'Categoriefp93.name',
			'Ficheprescription93.dd_action',
			'Ficheprescription93.df_action',
			'Ficheprescription93.statut',
			'/Fichesprescriptions93/edit/#Ficheprescription93.id#' => array(
				'disabled' => '!( "#/Fichesprescriptions93/edit#" && "'.$this->Permissions->checkDossier( 'Fichesprescriptions93', 'edit', $dossierMenu ).'" )'
			),
			'/Fichesprescriptions93/cancel/#Ficheprescription93.id#' => array(
				'disabled' => '!( "#/Fichesprescriptions93/cancel#" && "'.$this->Permissions->checkDossier( 'Fichesprescriptions93', 'cancel', $dossierMenu ).'" )'
			),
			'/Fichesprescriptions93/impression/#Ficheprescription93.id#' => array(
				'disabled' => '!( "#/Fichesprescriptions93/impression#" && "'.$this->Permissions->checkDossier( 'Fichesprescriptions93', 'impression', $dossierMenu ).'" )'
			),
		),
		array(
			'options' => $options,
			'paginate' => false
		)
	);
?>