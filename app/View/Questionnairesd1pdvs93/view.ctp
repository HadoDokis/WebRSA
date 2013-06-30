<?php
	echo $this->Default3->titleForLayout( $questionnaired1pdv93 );

	echo $this->Html->tag( 'h2', 'Réponses au questionnaire D1' );
	echo $this->Default3->view(
		$questionnaired1pdv93,
		array(
			'Questionnaired1pdv93.inscritpe' => array( 'type' => 'boolean' ),
			'Questionnaired1pdv93.marche_travail',
			'Questionnaired1pdv93.vulnerable',
			'Questionnaired1pdv93.diplomes_etrangers',
			'Questionnaired1pdv93.categorie_sociopro',
			'Questionnaired1pdv93.nivetu',
			'Questionnaired1pdv93.autre_caracteristique',
			'Questionnaired1pdv93.autre_caracteristique_autre',
			'Questionnaired1pdv93.conditions_logement',
			'Questionnaired1pdv93.conditions_logement_autre',
			'Questionnaired1pdv93.date_validation',
		),
		array(
			'options' => $options
		)
	);

	echo $this->Html->tag( 'h2', 'Photographie de la situation de l\'allocataire' );
	echo $this->Default3->view(
		$questionnaired1pdv93,
		array(
			'Situationallocataire.qual',
			'Situationallocataire.nom',
			'Situationallocataire.prenom',
			'Situationallocataire.nomnai',
			'Situationallocataire.nir',
			'Situationallocataire.sexe',
			'Situationallocataire.dtnai',
			'Situationallocataire.rolepers',
			'Situationallocataire.toppersdrodevorsa',
			'Situationallocataire.nati',
			'Situationallocataire.identifiantpe',
			'Situationallocataire.datepe',
			'Situationallocataire.etatpe',
			'Situationallocataire.codepe',
			'Situationallocataire.motifpe',
			'Situationallocataire.numvoie',
			'Situationallocataire.typevoie',
			'Situationallocataire.nomvoie',
			'Situationallocataire.complideadr',
			'Situationallocataire.compladr',
			'Situationallocataire.numcomptt',
			'Situationallocataire.numcomrat',
			'Situationallocataire.codepos',
			'Situationallocataire.locaadr',
			'Situationallocataire.numdemrsa',
			'Situationallocataire.matricule',
			'Situationallocataire.fonorg',
			'Situationallocataire.etatdosrsa',
			'Situationallocataire.sitfam',
			'Situationallocataire.nbenfants',
			'Situationallocataire.dtdemrsa',
			'Situationallocataire.dtdemrmi',
			'Situationallocataire.statudemrsa',
//			'Situationallocataire.numdepins',
//			'Situationallocataire.typeserins',
//			'Situationallocataire.numcomins',
//			'Situationallocataire.numagrins',
			'Situationallocataire.natpf_socle' => array( 'type' => 'boolean' ),
			'Situationallocataire.natpf_majore' => array( 'type' => 'boolean' ),
			'Situationallocataire.natpf_activite' => array( 'type' => 'boolean' ),
		),
		array(
			'options' => $options
		)
	);
?>
<p>
	<?php
		echo $this->Default->button(
			'back',
			array(
				'controller' => 'questionnairesd1pdvs93',
				'action' => 'index',
				$questionnaired1pdv93['Personne']['id']
			),
			array( 'id' => 'Back' )
		);
	?>
</p>