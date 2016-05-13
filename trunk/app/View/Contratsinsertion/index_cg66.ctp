<?php
	// Donne le domain du plus haut niveau de précision (prefix, action puis controller)
	$domain = current(MultiDomainsTranslator::urlDomains());
	$defaultParams = compact('options', 'domain');
	
	App::uses('WebrsaAccess', 'Utility');
	WebrsaAccess::init($dossierMenu);

	echo $this->Default3->titleForLayout($this->request->data, compact('domain'));
	echo $this->element('ancien_dossier');

	echo $this->Default3->actions(
		WebrsaAccess::actionAdd("/Contratsinsertion/add/{$personne_id}", $ajoutPossible)
	);

	// A-t'on des messages à afficher à l'utilisateur ?
	foreach ((array)$messages as $message => $class) {
		echo $this->Html->tag('p', __m($message), array('class' => "message {$class}"));
	}
	
	echo $this->Default3->index(
		$contratsinsertions,
		array(
			'Contratinsertion.forme_ci',
			'Contratinsertion.num_contrat_66',
			'Contratinsertion.dd_ci',
			'Contratinsertion.df_ci',
			'Contratinsertion.date_saisi_ci',
			'Contratinsertion.decision_ci',
			'Contratinsertion.datedecision',
			'Contratinsertion.positioncer',
		)
		+ WebrsaAccess::links(
			array(
				'/Contratsinsertion/view/#Contratinsertion.id#',
				'/Contratsinsertion/edit/#Contratinsertion.id#',
				'/Proposdecisionscers66/propositionsimple/#Contratinsertion.id#' => array(
					'condition' => "'#Contratinsertion.forme_ci#' === 'S'",
					'class' => 'button valider'
				),
				'/Proposdecisionscers66/propositionparticulier/#Contratinsertion.id#' => array(
					'condition' => "'#Contratinsertion.forme_ci#' !== 'S'",
					'class' => 'button valider'
				),
				'/Contratsinsertion/ficheliaisoncer/#Contratinsertion.id#',
				'/Contratsinsertion/notifbenef/#Contratinsertion.id#',
				'/Contratsinsertion/notificationsop/#Contratinsertion.id#' => array(
					'class' => 'button notifop'
				),
				'/Contratsinsertion/impression/#Contratinsertion.id#',
				'/Contratsinsertion/notification/#Contratinsertion.id#',
				'/Contratsinsertion/reconduction_cer_plus_55_ans/#Contratinsertion.id#' => array(
					'class' => 'button reconduction'
				),
				'/Contratsinsertion/cancel/#Contratinsertion.id#',
				'/Contratsinsertion/filelink/#Contratinsertion.id#' => array(
					'msgid' => __m('/Contratsinsertion/filelink').' (#Fichiermodule.count#)'
				)
			)
		),
		$defaultParams + array(
			'paginate' => false,
		)
	);