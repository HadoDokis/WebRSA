<?php
	// Donne le domain du plus haut niveau de précision (prefix, action puis controller)
	$domain = current(MultiDomainsTranslator::urlDomains());
	$defaultParams = compact('options', 'domain');

	echo $this->Default3->titleForLayout($this->request->data, compact('domain'));
	echo $this->FormValidator->generateJavascript();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
	
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'id' => 'FichedeliaisonAddForm' ) );

/***********************************************************************************
 * FORMULAIRE
/***********************************************************************************/
	
	echo '<fieldset>'
		. $this->Default3->subform(
			array(
				'Fichedeliaison.id' => array('type' => 'hidden'),
				'Fichedeliaison.expediteur_id' => array('empty' => true),
				'Fichedeliaison.destinataire_id' => array('empty' => true),
				'FichedeliaisonPersonne.personne_id' => array(
					'type' => 'select', 'multiple' => 'checkbox', 'options' => $concerne, 'fieldset' => true, 'required' => false
				),
				'Fichedeliaison.datefiche' => array('type' => 'date', 'dateFormat' => 'DMY'),
				'Fichedeliaison.motiffichedeliaison_id' => array('empty' => true),
				'Fichedeliaison.commentaire' => array('type' => 'textarea'),
			),
			$defaultParams
		) . '</fieldset>'
	;
	
/***********************************************************************************
 * FIN DU FORMULAIRE
/***********************************************************************************/
	
	echo $this->Default3->DefaultForm->buttons(array('Save', 'Cancel'));
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit('FichedeliaisonAddForm');
