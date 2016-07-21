<?php
	// Donne le domain du plus haut niveau de précision (prefix, action puis controller)
	$domain = current(WebrsaTranslator::domains());
	$defaultParams = compact('options', 'domain');

	echo $this->Default3->titleForLayout($this->request->data, compact('domain'));
	echo $this->FormValidator->generateJavascript();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

/***********************************************************************************
 * INFORMATIONS
/***********************************************************************************/

	echo $this->Default3->view(
		$this->request->data,
		array(
			'Fichedeliaison.etat' => array('empty' => true),
			'Fichedeliaison.expediteur_id' => array('empty' => true),
			'Fichedeliaison.destinataire_id' => array('empty' => true),
			'FichedeliaisonPersonne.personne_id' => array(
				'type' => 'select', 'multiple' => 'checkbox', 'options' => $concerne, 'fieldset' => true
			),
			'Fichedeliaison.datefiche' => array('type' => 'date', 'dateFormat' => 'DMY'),
			'Fichedeliaison.motiffichedeliaison_id' => array('empty' => true),
			'Fichedeliaison.commentaire' => array('type' => 'textarea'),
		),
		$defaultParams + array('th' => true, 'caption' => 'Fiche de liaison', 'class' => 'table-view')
	);

	echo $this->Default3->view(
		$this->request->data,
		array(
			'Avistechniquefiche.date' => array('type' => 'date', 'dateFormat' => 'DMY'),
			'Avistechniquefiche.choix' => array('type' => 'radio'),
			'Avistechniquefiche.commentaire' => array('type' => 'textarea'),
		),
		$defaultParams + array('th' => true, 'caption' => 'Avis technique', 'class' => 'table-view')
	);

	echo $this->Default3->view(
		$this->request->data,
		array(
			'Validationfiche.date' => array('type' => 'date', 'dateFormat' => 'DMY'),
			'Validationfiche.choix' => array('type' => 'radio'),
			'Validationfiche.commentaire' => array('type' => 'textarea'),
		),
		$defaultParams + array('th' => true, 'caption' => 'Validation', 'class' => 'table-view')
	);

	echo $this->Xhtml->link(
		'Retour',
		array('action' => 'index', $foyer_id)
	);