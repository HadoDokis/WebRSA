<?php
	// Donne le domain du plus haut niveau de précision (prefix, action puis controller)
	$domain = current(MultiDomainsTranslator::urlDomains());
	$defaultParams = compact('options', 'domain');

	echo $this->Default3->titleForLayout($this->request->data, compact('domain'));
	
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Cuis/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Cuis', 'add', $dossierMenu )
			),
		)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __m($message), array( 'class' => "message {$class}" ) );
		}
	}
	
	// Liste des permissions liés aux actions. 
	// Dans le cas d'un autre controller que Cuis66, on le renseigne avec Controller.action
	$perms = array(
		'view',
		'edit',
		'delete',
	);
	
	// Attribu à $perm[$nomDeLaction] la valeur 'true' ou 'false' (string)
	// Utile pour defaut3 avec son eval() 
	// ex: '( in_array( \'#Cui.etatdossiercui#\', array( \'annule\' ) ) ) || ' . $perm['edit']
	foreach( $perms as $permission ){
		$controllerName = 'Cuis';
		$actionName = $permission;
		
		if (strpos($permission, '.') !== false){
			list($controllerName, $actionName) = explode( '.', $permission );
		}
		
		$perm[$permission] = !$this->Permissions->checkDossier( $controllerName, $actionName, $dossierMenu ) ? 'true' : 'false';
	}
	
	echo $this->Default3->index(
		$results,
		array(
			'Cui.faitle',
			'Cui.secteurmarchand' => array( 'type' => 'select' ),
			'Partenairecui.raisonsociale',
			'Cui.effetpriseencharge',
			'Cui.finpriseencharge',
			'/Cuis/view/#Cui.id#' => array(
				'disabled' => $perm['view']
			),
			'/Cuis/edit/#Cui.id#' => array(
				'disabled' => '( in_array( \'#Cui66.etatdossiercui66#\', array( \'annule\' ) ) ) || ' . $perm['edit']
			),
			'/Cuis/delete/#Cui.id#' => array(
				'disabled' => $perm['delete']
			),
			'/Cuis/filelink/#Cui.id#' => array(
				'disabled' => !$this->Permissions->checkDossier( 'Cuis', 'filelink', $dossierMenu )
			),
			'Fichiermodule.nombre' => array( 'type' => 'integer', 'class' => 'number' ),
		),
		$defaultParams + array(
			'paginate' => false,
		)
	);
