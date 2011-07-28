<?php $this->pageTitle = 'APREs';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une APRE';
	}
	else {
		$this->pageTitle = 'APRE ';
		$foyer_id = $this->data['Personne']['foyer_id'];
	}
?>
<div class="with_treemenu">
	<h1><?php echo 'APRE  ';?></h1>

<?php
	$montantrestant = null;
	$montantaverser = Set::classicExtract( $apre, 'Apre.montantaverser' );
	$montantdejaverse = Set::classicExtract( $apre, 'Apre.montantdejaverse' );
	$montantrestant = ( $montantaverser - $montantdejaverse );
?>
<?php
		$typedemandeapre = Set::enum( $apre['Apre']['typedemandeapre'], $options['typedemandeapre'] );
		$naturelogement = Set::enum( $apre['Apre']['naturelogement'], $options['naturelogement'] );
		$activitebeneficiaire = Set::enum( $apre['Apre']['activitebeneficiaire'], $options['activitebeneficiaire'] );
		$typecontrat = Set::enum( $apre['Apre']['typecontrat'], $options['typecontrat'] );
		$statutapre = Set::enum( $apre['Apre']['statutapre'], $options['statutapre'] );
		$etatdossierapre = Set::enum( $apre['Apre']['etatdossierapre'], $options['etatdossierapre'] );
		$eligibiliteapre = Set::enum( $apre['Apre']['eligibiliteapre'], $options['eligibiliteapre'] );
		$justificatif = Set::enum( $apre['Apre']['justificatif'], $options['justificatif'] );
		$isdecision = Set::enum( $apre['Apre']['isdecision'], $options['isdecision'] );
		$referent = Set::enum( $apre['Apre']['referent_id'], $referents );
		$struct = Set::enum( $apre['Apre']['structurereferente_id'], $structs );
		$cessderact = Set::enum( $apre['Apre']['cessderact'], $optionsdsps['cessderact'] );
		
// 		debug( $apre );
		echo $default2->view(
			$apre,
			array(
				'Personne.nom_complet' => array( 'type' => 'text' ),
				'Apre.numeroapre',
				'Apre.typedemandeapre' => array( 'value' => $typedemandeapre ),
				'Apre.datedemandeapre',
				'Apre.naturelogement' => array( 'value' => $naturelogement ),
				'Apre.precisionsautrelogement',
				'Apre.anciennetepoleemploi' => array( 'type' => 'text' ),
				'Apre.projetprofessionnel' => array( 'type' => 'text' ),
				'Apre.secteurprofessionnel' => array( 'type' => 'text' ),
				'Apre.activitebeneficiaire' => array( 'value' => $activitebeneficiaire ),
				'Apre.dateentreeemploi',
				'Apre.typecontrat' => array( 'value' => $typecontrat ),
				'Apre.precisionsautrecontrat' => array( 'type' => 'text' ),
				'Apre.nbheurestravaillees' => array( 'type' => 'text' ),
				'Apre.nomemployeur' => array( 'type' => 'text' ),
				'Apre.adresseemployeur' => array( 'type' => 'text' ),
				'Apre.avistechreferent' => array( 'type' => 'text' ),
				'Apre.etatdossierapre' => array( 'value' => $etatdossierapre ),
				'Apre.eligibiliteapre' => array( 'value' => $eligibiliteapre ),
				'Apre.secteuractivite' => array( 'type' => 'text' ),
				'Apre.nbenf12' => array( 'type' => 'text' ),
				'Apre.statutapre' => array( 'value' => $statutapre ),
				'Apre.justificatif' => array( 'value' => $justificatif ),
				'Apre.structurereferente_id' => array( 'type' => 'text', 'value' => $struct ),
				'Apre.referent_id' => array( 'type' => 'text', 'value' => $referent ),
				'Apre.montantaverser' => array( 'type' => 'text' ),
				'Apre.nbpaiementsouhait' => array( 'type' => 'text' ),
				'Apre.montantdejaverse' => array( 'type' => 'text' ),
				'Apre.cessderact' => array( 'value' => $cessderact  )/*,
				'Apre.isdecision' => array( 'value' => $isdecision )*/
			),
			array(
				'class' => 'aere',
				'id' => 'vueContrat',
				'domain' => 'apre'
			)
		);
// debug( $options );
?>
<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'apres',
			'action'     => 'index',
			$personne_id
		),
		array(
			'id' => 'Back'
		)
	);
?>

</div>
<div class="clearer"><hr /></div>