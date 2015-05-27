<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Suspensionscuis66/add/{$cui_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Suspensionscuis66', 'add', $dossierMenu ),
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
	
	$perm['view'] = !$this->Permissions->checkDossier( 'Suspensionscuis66', 'view', $dossierMenu ) ? 'true' : 'false';
	$perm['edit'] = !$this->Permissions->checkDossier( 'Suspensionscuis66', 'edit', $dossierMenu ) ? 'true' : 'false';
	$perm['delete'] = !$this->Permissions->checkDossier( 'Suspensionscuis66', 'delete', $dossierMenu ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Suspensioncui66.datedebut',
			'Suspensioncui66.datefin',
			'Suspensioncui66.duree',
			'Suspensioncui66.motif' => array( 'type' => 'select' ),
			'/Suspensionscuis66/view/#Suspensioncui66.id#/' => array(
				'title' => __d('suspensionscuis66', '/Suspensionscuis66/view'),
				'disabled' => $perm['view']
			),
			'/Suspensionscuis66/edit/#Suspensioncui66.id#/' => array(
				'title' => __d('suspensionscuis66', '/Suspensionscuis66/edit'),
				'class' => 'edit',
				'disabled' => '( in_array( \'#Cui66.etatdossiercui66#\', array( \'annule\' ) ) ) || ' . $perm['edit']
			),
			'/Suspensionscuis66/delete/#Suspensioncui66.id#/' => array(
				'title' => __d('suspensionscuis66', '/Suspensionscuis66/delete'),
				'class' => 'edit',
				'disabled' => '( in_array( \'#Cui66.etatdossiercui66#\', array( \'annule\' ) ) ) || ' . $perm['delete']
			),
			'/Suspensionscuis66/filelink/#Suspensioncui66.id#' => array(
				'title' => __d('suspensionscuis66', '/Suspensionscuis66/filelink'),
				'disabled' => !$this->Permissions->checkDossier( 'Suspensionscuis66', 'filelink', $dossierMenu )
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