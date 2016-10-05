<?php
	$formId = 'DossiersepsChoose'.$theme.'Form';
	echo $this->Default3->DefaultForm->create(null, array('id' => $formId));
	
	echo $this->Default3->index(
		$dossiers[$theme],
		$this->Translator->normalize(
			array(
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.nomcom',
				'Dossierep.created',
				'Foyer.enerreur' => array('type' => 'string', 'class' => 'foyer_enerreur'),
				'data[Passagecommissionep][][chosen]' => array('type' => 'checkbox', 'label' => false),
				'/Personnes/view/#Personne.id#',
				'/Dossierseps/disable/#Dossierep.id#' => array(
					'disabled' => "'#Dossierep.is_reporte#' !== '1'",
					'confirm' => true,
					'class' => 'cancel'
				),
			)
		),
		array(
			'options' => $options,
			'paginate' => Inflector::classify($theme),
			'id' => $theme,
		)
	);
	
	// Champs cachés
	foreach ($dossiers[$theme] as $key => $dossier) {
		echo $this->Xform->input('Dossierep.'.$key.'.id', array(
			'type' => 'hidden',
			'value' => Hash::get($dossier, 'Dossierep.id'),
		));
		echo $this->Xform->input('Passagecommissionep.'.$key.'.id', array(
			'type' => 'hidden',
			'value' => Hash::get($dossier, 'Passagecommissionep.id'),
		));
	}
	
	echo $this->Xform->input('Choose.theme', array(
		'type' => 'hidden',
		'value' => $theme,
	));
	
	echo $this->Default3->DefaultForm->buttons(array('Save', 'Cancel'));
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit($formId);