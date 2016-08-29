<?php
	$this->pageTitle = 'Rendez-vous de la personne';
	
	$messages = array();
	if (!empty($dossierep)) {
		$messages['Ce dossier est en cours de passage en EP : '.$dossierep['StatutrdvTyperdv']['motifpassageep']] = 'error';
	}
	if (!empty($dossiercov)) {
		$messages['Ce dossier est en cours de passage en COV: '.$dossiercov['StatutrdvTyperdv']['motifpassageep']] = 'error';
	}
	$paramsElement = array(
		'messages' => $messages,
	);
	echo $this->element('default_index', $paramsElement);
	
	foreach ($rdvs as $key => $rdv) {
		$thematiques = Hash::extract($rdv, 'Thematiquerdv.{n}.name');
		if (!empty($thematiques)) {
			$rdvs[$key]['Thematiquerdv']['name'] = '<ul><li>'.implode('</li><li>', $thematiques).'</li></ul>';
		}
	}
	
	echo $this->Default3->index(
		$rdvs,
		$this->Translator->normalize(
			array(
				'Personne.nom_complet',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Permanence.libpermanence',
				'Typerdv.libelle',
				'Statutrdv.libelle',
				'Rendezvous.daterdv',
				'Rendezvous.heurerdv' => array('format' => '%Hh%M'),
			) + WebrsaAccess::links(
				array(
					'/Rendezvous/view/#Rendezvous.id#',
					'/Rendezvous/edit/#Rendezvous.id#',
					'/Rendezvous/impression/#Rendezvous.id#',
					'/Rendezvous/delete/#Rendezvous.id##d1' => array(
						'condition' => "'#Rendezvous.has_questionnaired1pdv93#' == true",
						'confirm' => true,
					),
					'/Rendezvous/delete/#Rendezvous.id#' => array(
						'condition' => "'#Rendezvous.has_questionnaired1pdv93#' == false",
						'confirm' => true,
					),
					'/Rendezvous/filelink/#Rendezvous.id#',
				)
			)
		),
		array(
			'paginate' => false,
			'empty_label' => __m('Rendezvous::index::emptyLabel'),
			'innerTable' => $this->Translator->normalize(
				array(
					'Rendezvous.objetrdv',
					'Rendezvous.commentairerdv',
					'Thematiquerdv.name' => array(
						'condition' => "'#Thematiquerdv.name#' !== ''",
					)
				)
			)
		)
	);