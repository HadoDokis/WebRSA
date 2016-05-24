<?php
	// Donne le domain du plus haut niveau de précision (prefix, action puis controller)
	$domain = current(MultiDomainsTranslator::urlDomains());
	$defaultParams = compact('options', 'domain');
	App::uses('WebrsaAccess', 'Utility');
	WebrsaAccess::init($dossierMenu);

	echo $this->Default3->titleForLayout($this->request->data, compact('domain'));
	
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		WebrsaAccess::actionAdd("/Cuis/add/{$personne_id}", $ajoutPossible)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __m($message), array( 'class' => "message {$class}" ) );
		}
	}
	
	echo $this->Default3->index(
		$results,
		array(
			'Cui.faitle',
			'Cui.secteurmarchand' => array( 'type' => 'select' ),
			'Partenairecui.raisonsociale',
			'Cui.effetpriseencharge',
			'Cui.finpriseencharge',
		) + WebrsaAccess::links(
			array(
				'/Cuis/view/#Cui.id#',
				'/Cuis/edit/#Cui.id#',
				'/Cuis/delete/#Cui.id#',
				'/Cuis/filelink/#Cui.id#' => array(
					'msgid' => __m('/Cuis/filelink')." (#Fichiermodule.nombre#)",
				),
			)
		),
		$defaultParams + array(
			'paginate' => false,
		)
	);
