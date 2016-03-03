<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Cuis66/email_add/{$personne_id}/{$cui_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Cuis66', 'email_add', $dossierMenu ),
				'class' => 'add'
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( $this->request->params['controller'], $message ), array( 'class' => "message {$class}" ) );
		}
	}
	
	$email_send = !$this->Permissions->checkDossier( 'Cuis66', 'email_send', $dossierMenu ) ? 'true' : 'false';
	$email_delete = !$this->Permissions->checkDossier( 'Cuis66', 'email_delete', $dossierMenu ) ? 'true' : 'false';
	$email_edit = !$this->Permissions->checkDossier( 'Cuis66', 'email_edit', $dossierMenu ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Emailcui.titre',
			'Emailcui.created',
			'Emailcui.dateenvoi',
			'/Cuis66/email_send/#Emailcui.personne_id#/#Emailcui.cui_id#/#Emailcui.id#' => array(
				'title' => __d('cuis66', '/Cuis66/email_send'),
				'disabled' => '( "#Emailcui.dateenvoi#" ) || ' . $email_send
			),
			'/Cuis66/email_view/#Emailcui.personne_id#/#Emailcui.id#' => array(
				'title' => __d('cuis66', '/Cuis66/email_view'),
				'class' => 'view',
				'disabled' => !$this->Permissions->checkDossier( 'Cuis66', 'email_view', $dossierMenu )
			),
			'/Cuis66/email_edit/#Emailcui.personne_id#/#Emailcui.id#' => array(
				'title' => __d('cuis66', '/Cuis66/email_edit'),
				'class' => 'edit',
				'disabled' => '( "#Emailcui.dateenvoi#" ) || ' . $email_edit
			),
			'/Cuis66/email_delete/#Emailcui.id#' => array(
				'title' => __d('cuis66', '/Cuis66/email_delete'),
				'class' => 'delete',
				'disabled' => '( "#Emailcui.dateenvoi#" ) || ' . $email_delete
			),
		),
		array(
			'options' => $options,
			'paginate' => false
		)
	);
	
	echo '<br />' . $this->Default->button(
		'back',
		array(
			'controller' => 'cuis',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back',
			'class' => 'aere'
		)
	);
	