<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Propositionscuis66/add/{$cui_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Propositionscuis66', 'add', $dossierMenu ),
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
	
	$perm['view'] = !$this->Permissions->checkDossier( 'Propositionscuis66', 'view', $dossierMenu ) ? 'true' : 'false';
	$perm['edit'] = !$this->Permissions->checkDossier( 'Propositionscuis66', 'edit', $dossierMenu ) ? 'true' : 'false';
	$perm['impression'] = !$this->Permissions->checkDossier( 'Propositionscuis66', 'impression', $dossierMenu ) ? 'true' : 'false';
	$perm['impression_aviselu'] = !$this->Permissions->checkDossier( 'Propositionscuis66', 'impression_aviselu', $dossierMenu ) ? 'true' : 'false';
	$perm['delete'] = !$this->Permissions->checkDossier( 'Propositionscuis66', 'delete', $dossierMenu ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Propositioncui66.donneuravis',
			'Propositioncui66.dateproposition',
			'Propositioncui66.avis',
			'/Propositionscuis66/view/#Propositioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/view'),
				'disabled' => $perm['view']
			),
			'/Propositionscuis66/edit/#Propositioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/edit'),
				'class' => 'edit',
				'disabled' => $perm['edit']
			),
			'/Propositionscuis66/impression_aviselu/#Propositioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/impression_aviselu'),
				'class' => 'impression',
				'disabled' => $perm['impression_aviselu']
			),
			'/Propositionscuis66/impression/#Propositioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/impression'),
				'class' => 'impression',
				'disabled' => $perm['impression']
			),
			'/Propositionscuis66/delete/#Propositioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/delete'),
				'class' => 'edit',
				'disabled' => $perm['delete']
			),
			'/Propositionscuis66/filelink/#Propositioncui66.id#' => array(
				'title' => __d('propositionscuis66', '/Propositionscuis66/filelink'),
				'disabled' => !$this->Permissions->checkDossier( 'Propositionscuis66', 'filelink', $dossierMenu )
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