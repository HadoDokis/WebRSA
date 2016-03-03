<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Accompagnementscuis66/add/{$cui_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'add', $dossierMenu ),
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
	
	$perm['view'] = !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'view', $dossierMenu ) ? 'true' : 'false';
	$perm['edit'] = !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'edit', $dossierMenu ) ? 'true' : 'false';
	$perm['delete'] = !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'delete', $dossierMenu ) ? 'true' : 'false';
	$perm['impression'] = !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'impression', $dossierMenu ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Accompagnementcui66.genre',
			'Accompagnementcui66.organismesuivi',
			'Accompagnementcui66.datededebut',
			'Accompagnementcui66.datedefin',
			'/Accompagnementscuis66/view/#Accompagnementcui66.id#/' => array(
				'title' => __d('accompagnementscuis66', '/Accompagnementscuis66/view'),
				'disabled' => $perm['view']
			),
			'/Accompagnementscuis66/edit/#Accompagnementcui66.id#/' => array(
				'title' => __d('accompagnementscuis66', '/Accompagnementscuis66/edit'),
				'class' => 'edit',
				'disabled' => $perm['edit']
			),
			'/Accompagnementscuis66/impression/#Accompagnementcui66.id#/' => array(
				'title' => __d('accompagnementscuis66', '/Accompagnementscuis66/impression'),
				'class' => 'impression',
				'disabled' => $perm['impression']
			),
			'/Accompagnementscuis66/delete/#Accompagnementcui66.id#/' => array(
				'title' => __d('accompagnementscuis66', '/Accompagnementscuis66/delete'),
				'class' => 'edit',
				'disabled' => $perm['delete']
			),
			'/Accompagnementscuis66/filelink/#Accompagnementcui66.id#' => array(
				'title' => __d('accompagnementscuis66', '/Accompagnementscuis66/filelink'),
				'disabled' => !$this->Permissions->checkDossier( 'Accompagnementscuis66', 'filelink', $dossierMenu )
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
			'controller' => 'cuis',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back',
			'class' => 'aere'
		)
	);