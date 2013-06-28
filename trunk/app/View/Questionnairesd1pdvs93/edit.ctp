<?php
	echo $this->Default3->titleForLayout( $personne );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Default3->form(
		array(
			'Situationallocataire.id' => array( 'type' => 'hidden' ),
			'Situationallocataire.personne_id' => array( 'type' => 'hidden' ),
			'Situationallocataire.qual' => array( 'type' => 'hidden' ),
			'Situationallocataire.nom' => array( 'type' => 'hidden' ),
			'Situationallocataire.prenom' => array( 'type' => 'hidden' ),
			'Situationallocataire.nomnai' => array( 'type' => 'hidden' ),
			'Situationallocataire.nir' => array( 'type' => 'hidden' ),
			'Situationallocataire.sexe' => array( 'type' => 'hidden' ),
			'Situationallocataire.dtnai' => array( 'type' => 'hidden' ),
			'Situationallocataire.rolepers' => array( 'type' => 'hidden' ),
			'Situationallocataire.toppersdrodevorsa' => array( 'type' => 'hidden' ),
			'Situationallocataire.nati' => array( 'type' => 'hidden' ),
			'Situationallocataire.identifiantpe' => array( 'type' => 'hidden' ),
			'Situationallocataire.datepe' => array( 'type' => 'hidden' ),
			'Situationallocataire.etatpe' => array( 'type' => 'hidden' ),
			'Situationallocataire.codepe' => array( 'type' => 'hidden' ),
			'Situationallocataire.motifpe' => array( 'type' => 'hidden' ),
			'Situationallocataire.numvoie' => array( 'type' => 'hidden' ),
			'Situationallocataire.typevoie' => array( 'type' => 'hidden' ),
			'Situationallocataire.nomvoie' => array( 'type' => 'hidden' ),
			'Situationallocataire.complideadr' => array( 'type' => 'hidden' ),
			'Situationallocataire.compladr' => array( 'type' => 'hidden' ),
			'Situationallocataire.numcomptt' => array( 'type' => 'hidden' ),
			'Situationallocataire.numcomrat' => array( 'type' => 'hidden' ),
			'Situationallocataire.codepos' => array( 'type' => 'hidden' ),
			'Situationallocataire.locaadr' => array( 'type' => 'hidden' ),
			'Situationallocataire.numdemrsa' => array( 'type' => 'hidden' ),
			'Situationallocataire.matricule' => array( 'type' => 'hidden' ),
			'Situationallocataire.fonorg' => array( 'type' => 'hidden' ),
			'Situationallocataire.etatdosrsa' => array( 'type' => 'hidden' ),
			'Situationallocataire.sitfam' => array( 'type' => 'hidden' ),
			'Situationallocataire.nbenfants' => array( 'type' => 'hidden' ),
			'Situationallocataire.dtdemrsa' => array( 'type' => 'hidden' ),
			'Situationallocataire.dtdemrmi' => array( 'type' => 'hidden' ),
			'Situationallocataire.statudemrsa' => array( 'type' => 'hidden' ),
			'Situationallocataire.numdepins' => array( 'type' => 'hidden' ),
			'Situationallocataire.typeserins' => array( 'type' => 'hidden' ),
			'Situationallocataire.numcomins' => array( 'type' => 'hidden' ),
			'Situationallocataire.numagrins' => array( 'type' => 'hidden' ),
//			'Situationallocataire.natpf_serialize' => array( 'type' => 'hidden' ),
			'Situationallocataire.natpf_socle' => array( 'type' => 'hidden' ),
			'Situationallocataire.natpf_majore' => array( 'type' => 'hidden' ),
			'Situationallocataire.natpf_activite' => array( 'type' => 'hidden' ),
			'Questionnaired1pdv93.id' => array( 'type' => 'hidden' ),
			'Questionnaired1pdv93.personne_id' => array( 'type' => 'hidden' ),
			'Questionnaired1pdv93.rendezvous_id' => array(
				'options' => $options['Questionnaired1pdv93']['rendezvous_id'],
				'empty' => true
			),
			'Questionnaired1pdv93.inscritpe' => array(
				'options' => $options['Questionnaired1pdv93']['inscritpe'],
				'empty' => true
			),
			'Questionnaired1pdv93.marche_travail' => array(
				'options' => $options['Questionnaired1pdv93']['marche_travail'],
				'empty' => true
			),
			'Questionnaired1pdv93.vulnerable' => array(
				'options' => $options['Questionnaired1pdv93']['vulnerable'],
				'empty' => true
			),
			'Questionnaired1pdv93.diplomes_etrangers' => array(
				'options' => $options['Questionnaired1pdv93']['diplomes_etrangers'],
				'empty' => true
			),
			'Questionnaired1pdv93.categorie_sociopro' => array(
				'options' => $options['Questionnaired1pdv93']['categorie_sociopro'],
				'empty' => true
			),
			'Questionnaired1pdv93.nivetu' => array( // FIXME: visualisation
				'options' => $options['Questionnaired1pdv93']['nivetu'],
				'empty' => true,
			),
			'Questionnaired1pdv93.autre_caracteristique' => array( // FIXME: visualisation
				'options' => $options['Questionnaired1pdv93']['autre_caracteristique'],
				'empty' => true
			),
			'Questionnaired1pdv93.autre_caracteristique_autre', // FIXME: visualisation/vide
			'Questionnaired1pdv93.conditions_logement' => array(
				'options' => $options['Questionnaired1pdv93']['conditions_logement'],
				'empty' => true
			),
			'Questionnaired1pdv93.conditions_logement_autre',
			'Questionnaired1pdv93.date_validation' => array(
				'type' => 'hidden',
				'empty' => true
			),
		),
		array(
			'buttons' => array( 'Validate', 'Cancel' )
		)
	);
?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue(
			'Questionnaired1pdv93AutreCaracteristique',
			[ 'Questionnaired1pdv93AutreCaracteristiqueAutre' ],
			[ 'autres' ],
			false,
			false
		);

		observeDisableFieldsOnValue(
			'Questionnaired1pdv93ConditionsLogement',
			[ 'Questionnaired1pdv93ConditionsLogementAutre' ],
			[ 'autre' ],
			false,
			false
		);
	} );
</script>