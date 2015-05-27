<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	if ( empty($results) ){
		echo $this->Default3->actions(
			array(
				"/Rupturescuis66/add/{$cui_id}" => array(
					'disabled' => !$this->Permissions->checkDossier( 'Rupturescuis66', 'add', $dossierMenu ) || in_array( $etatdossiercui66, array( 'annule' ) ),
					'class' => 'add'
				),
			)
		);
	}

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( $this->request->params['controller'], $message ), array( 'class' => "message {$class}" ) );
		}
	}

	$perm['edit'] = !$this->Permissions->checkDossier( 'Rupturescuis66', 'edit', $dossierMenu ) || in_array( $etatdossiercui66, array( 'annule' ) ) ? 'true' : 'false';
	$perm['delete'] = !$this->Permissions->checkDossier( 'Rupturescuis66', 'delete', $dossierMenu ) || in_array( $etatdossiercui66, array( 'annule' ) ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Rupturecui66.observation',
			'Rupturecui66.daterupture',
			'Rupturecui66.dateenregistrement',
			'Rupturecui66.motif' => array( 'type' => 'select' ),
			'/Rupturescuis66/edit/#Rupturecui66.id#/' => array(
				'title' => __d('rupturescuis66', '/Rupturescuis66/edit'),
				'class' => 'edit',
				'disabled' => $perm['edit']
			),
			'/Rupturescuis66/delete/#Rupturecui66.id#/' => array(
				'title' => __d('rupturescuis66', '/Rupturescuis66/delete'),
				'class' => 'edit',
				'disabled' => $perm['delete']
			),
			'/Rupturescuis66/filelink/#Rupturecui66.id#' => array(
				'title' => __d('rupturescuis66', '/Rupturescuis66/filelink'),
				'disabled' => !$this->Permissions->checkDossier( 'Rupturescuis66', 'filelink', $dossierMenu )
			),
			'Fichiermodule.nombre' => array( 'type' => 'integer', 'class' => 'number' ),
		),
		array(
			'options' => $options,
			'paginate' => false,
		)
	);

	echo '<br />' . $this->Default->button(
		'back',
		array(
			'controller' => 'cuis66',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back',
			'class' => 'aere'
		)
	);