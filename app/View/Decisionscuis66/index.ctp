<?php
	echo $this->Default3->titleForLayout();

	echo $this->element( 'ancien_dossier' );
	
	if ( empty($results) ){
		echo $this->Default3->actions(
			array(
				"/Decisionscuis66/add/{$cui_id}" => array(
					'disabled' => !$this->Permissions->checkDossier( 'Decisionscuis66', 'add', $dossierMenu ) || in_array( $etatdossiercui66, array( 'annule' ) ),
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
	
	$perm['edit'] = !$this->Permissions->checkDossier( 'Decisionscuis66', 'edit', $dossierMenu ) ? 'true' : 'false';
	$perm['delete'] = !$this->Permissions->checkDossier( 'Decisionscuis66', 'delete', $dossierMenu ) ? 'true' : 'false';

	echo $this->Default3->index(
		$results,
		array(
			'Decisioncui66.datedecision',
			'Decisioncui66.decision',
			'Decisioncui66.motif' => array( 'type' => 'select' ),
			'Decisioncui66.observation',
			'/Decisionscuis66/edit/#Decisioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Decisionscuis66/edit'),
				'class' => 'edit',
				'disabled' => '( in_array( \'#Cui66.etatdossiercui66#\', array( \'annule\' ) ) ) || ' . $perm['edit']
			),
			'/Decisionscuis66/delete/#Decisioncui66.id#/' => array(
				'title' => __d('propositionscuis66', '/Decisionscuis66/delete'),
				'class' => 'edit',
				'disabled' => '( in_array( \'#Cui66.etatdossiercui66#\', array( \'annule\' ) ) ) || ' . $perm['delete']
			),
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