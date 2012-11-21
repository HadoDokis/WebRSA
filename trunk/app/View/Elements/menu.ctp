<?php if( $this->Session->check( 'Auth.User' ) ): ?>
<div id="menu1Wrapper">
	<div class="menu1">
<?php
	// FIXME: title
	// FIXME: réécriture des clés
	// TODO: javascript
	$items = array(
		( Configure::read( 'Cg.departement' ) == 66 ? 'Gestion de listes' : 'Cohortes' ) => array(
			'APRE' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'À valider' => array( 'url' => array( 'controller' => 'cohortesvalidationapres66', 'action' => 'apresavalider' ) ),
				'À notifier' => array( 'url' => array( 'controller' => 'cohortesvalidationapres66', 'action' => 'validees' ) ),
				'Notifiées' => array( 'url' => array( 'controller' => 'cohortesvalidationapres66', 'action' => 'notifiees' ) ),
				'Transfert cellule' => array( 'url' => array( 'controller' => 'cohortesvalidationapres66', 'action' => 'transfert' ) ),
				'Traitement cellule' => array( 'url' =>  array( 'controller' => 'cohortesvalidationapres66', 'action' => 'traitement' ) ),
			),
			'CER' => array(
				'disabled' => ( !in_array( Configure::read( 'Cg.departement' ), array( 66, 93 ) ) ),
				'Contrats Simples à valider' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
					'url' => array( 'controller' => 'cohortesci', 'action' => 'nouveauxsimple' )
				),
				'Contrats Particuliers à valider' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
					'url' => array( 'controller' => 'cohortesci', 'action' => 'nouveauxparticulier' )
				),
				'Décisions prises' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
					'url' => array( 'controller' => 'cohortesci', 'action' => 'valides' )
				),
				'Contrats à valider' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
					'url' => array( 'controller' => 'cohortesci', 'action' => 'nouveaux' )
				),
				'Contrats validés' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
					'url' => array( 'controller' => 'cohortesci', 'action' => 'valides' )
				),
			),
			'Fiches de candidature' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Fiches en attente' => array( 'url' => array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesenattente' ) ),
				'Fiches en cours' => array( 'url' => array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesencours' ) ),
			),
			'Dossiers PCGs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Dossiers en attente d\'affectation' => array( 'url' => array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'enattenteaffectation' ) ),
				'Dossiers affectés' => array( 'url' => array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'affectes' ) ),
				'Dossiers à imprimer' => array( 'url' => array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'aimprimer' ) ),
				'Dossiers à transmettre' => array( 'url' => array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'atransmettre' ) ),
			),
			'Non orientation' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Inscrits PE' => array( 'url' => array( 'controller' => 'cohortesnonorientes66', 'action' => 'isemploi' ) ),
				'Non inscrits PE' => array( 'url' => array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploiaimprimer' ) ),
				'Gestion des réponses' => array( 'url' => array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploi' ) ),
				'Notifications à envoyer' => array( 'url' => array( 'controller' => 'cohortesnonorientes66', 'action' => 'notifaenvoyer' ) ),
				'Orientés et notifiés' => array( 'url' =>  array( 'controller' => 'cohortesnonorientes66', 'action' => 'oriente' ) ),
			),
			'Orientation' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'Demandes non orientées' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'nouvelles' ) ),
				'Demandes en attente de validation d\'orientation' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'enattente' ) ),
				'Demandes orientées' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'orientees' ) ),
			),
			'PDOs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'Nouvelles demandes' => array( 'url' => array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ) ),
				'Liste PDOs' => array( 'url' => array( 'controller' => 'cohortespdos', 'action' => 'valide' ) ),
			),
			'EPs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'Relances (EP)' => array(
					__d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true ) => array( 'url' => array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'cohorte' ) ),
					__d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true ) => array( 'url' => array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impressions' ) ),
				),
				'Parcours social sans réorientation' => array( 'url' => array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) ),
				'Radiés de Pôle Emploi' => array( 'url' => array( 'controller' => 'nonrespectssanctionseps93', 'action' => 'selectionradies'  ) ),
			),
		),
		'Recherches' => array(
			'Par dossier / allocataire' => array( 'url' => array( 'controller' => 'dossiers', 'action' => 'index' ) ),
			'Par Orientation' => array( 'url' => array( 'controller' => 'criteres', 'action' => 'index' ) ),
			'Par APREs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresapres', 'action' => 'all' )
			),
			'Par Contrats' => array(
				'Par CER' => array( 'url' => array( 'controller' => 'criteresci', 'action' => 'index'  ) ),
				'Par CUI' => array( 'url' => array( 'controller' => 'criterescuis', 'action' => 'index'  ) ),
			),
			'Par Entretiens' => array( 'url' => array( 'controller' => 'criteresentretiens', 'action' => 'index' ) ),
			'Par Fiches de candidature' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresfichescandidature', 'action' => 'index' )
			),
			'Par Indus' => array( 'url' => array( 'controller' => 'cohortesindus', 'action' => 'index' ) ),
			'Par DSPs' => array( 'url' => array( 'controller' => 'dsps', 'action' => 'index' ) ),
			'Par Rendez-vous' => array( 'url' => array( 'controller' => 'criteresrdv', 'action' => 'index'  ) ),
			'Par Dossiers PCGs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Dossiers PCGs' => array( 'url' => array( 'controller' => 'criteresdossierspcgs66', 'action' => 'dossier'  ) ),
				'Traitements PCGs' => array( 'url' => array( 'controller' => 'criterestraitementspcgs66', 'action' => 'index'  ) ),
				'Gestionnaires PCGs' => array( 'url' => array( 'controller' => 'criteresdossierspcgs66', 'action' => 'gestionnaire'  ) ),
			),
			'Par PDOs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) == 66 ),
				'Nouvelles PDOs' => array( 'url' => array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) ),
				'Liste des PDOs' => array( 'url' => array( 'controller' => 'criterespdos', 'action' => 'index'  ) ),
			),
			'Par Dossiers COV' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
				'url' => array( 'controller' => 'criteresdossierscovs58', 'action' => 'index'  ),
				'Pôle Emploi' => array(
					'Radiation de Pôle Emploi' => array( 'url' => array( 'controller' => 'sanctionseps58', 'action' => 'selectionradies' ) ),
					'Non inscription à Pôle Emploi' => array( 'url' => array( 'controller' => 'sanctionseps58', 'action' => 'selectionnoninscrits' ) ),
				),
				'Demande de maintien dans le social' => array( 'url' => array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) ),
			),
			'Par Bilans de parcours' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresbilansparcours66', 'action' => 'index'  ),
			),
			'Pôle Emploi' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Non inscrits au Pôle Emploi' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionnoninscrits'  ) ),
				'Radiés de Pôle Emploi' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionradies'  ) ),
			),
			'Demande de maintien dans le social' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'nonorientationsproseps', 'action' => 'index'  )
			),
		),
		'APRE' => array(
			'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
			'Liste des demandes d\'APRE' => array(
				'Toutes les APREs' => array( 'url' => array( 'controller' => 'criteresapres', 'action' => 'all' ) ),
				'Eligibilité des APREs' => array( 'url' => array( 'controller' => 'criteresapres', 'action' => 'eligible' ) ),
				'Demande de recours' => array( 'url' => array( 'controller' => 'recoursapres', 'action' => 'demande' ) ),
				'Visualisation des recours' => array( 'url' => array( 'controller' => 'recoursapres', 'action' => 'visualisation' ) ),
			),
			'Comité d\'examen' => array(
				'Recherche de Comité' => array( 'url' => array( 'controller' => 'comitesapres', 'action' => 'index' ) ),
				'Gestion des décisions Comité' => array( 'url' => array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite' ) ),
				'Notifications décisions Comité' => array( 'url' => array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite' ) ),
				'Liste des Comités' => array( 'url' => array( 'controller' => 'comitesapres', 'action' => 'liste' ) ),
			),
			'Reporting bi-mensuel' => array(
				'Reporting bi-mensuel DDTEFP' => array( 'url' => array( 'controller' => 'repsddtefp', 'action' => 'index' ) ),
				'Suivi et contrôle de l\'enveloppe APRE' => array( 'url' => array( 'controller' => 'repsddtefp', 'action' => 'suivicontrole' ) ),
			),
			'Journal d\'intégration des fichiers CSV' => array( 'url' => array( 'controller' => 'integrationfichiersapre', 'action' => 'index' ) ),
			'États liquidatifs APRE' => array( 'url' => array( 'controller' => 'etatsliquidatifs', 'action' => 'index' ) ),
			'Budgets APRE' => array( 'url' => array( 'controller' => 'budgetsapres', 'action' => 'index' ) ),
		),
		'COV' => array(
			'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
			'url' => array( 'controller' => 'covs58', 'action' => 'index' ),
		),
		'Offre d\'Insertion' => array(
			'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
			'url' => array( 'controller' => 'offresinsertion', 'action' => 'index' ),
		),
		'Eq. Pluri.' => array(
			( Configure::read( 'Cg.departement' ) == 66 ? '1. Gestion des EPs' : '1. Mise en place du dispositif' ) => array(
				'Courriers d\'information avant EPL Audition' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
					'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'courriersinformations'  ),
				),
				'Création des membres' => array( 'url' => array( 'controller' => 'membreseps', 'action' => 'index' ) ),
				'Création des EPs' => array( 'url' => array( 'controller' => 'eps', 'action' => 'index' ) ),
				'Création des Commissions' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'add' ) ),
			),
			( Configure::read( 'Cg.departement' ) == 66 ? '2. Recherche de commission' : '2. Constitution de la commission' ) => array(
				'url' => array( 'controller' => 'commissionseps', 'action' => 'recherche' ),
			),
			'3. Arbitrage EP' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
				'url' => array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ),
			),
			( Configure::read( 'Cg.departement' ) == 66 ? '3. Avis/Décisions' : '3. Arbitrage' ) => array(
				( Configure::read( 'Cg.departement' ) == 66 ? 'Avis EP' : 'EP' ) => array(
					'url' => array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ),
				),
				( Configure::read( 'Cg.departement' ) == 66 ? 'Décisions CG' : 'CG' ) => array(
					'url' => array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ),
				),
			),
			'4. Consultation et impression des décisions' => array(
				'url' => array( 'controller' => 'commissionseps', 'action' => 'decisions' ),
			),
			'5. Gestion des sanctions' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
				'Gestion des sanctions' => array( 'url' => array( 'controller' => 'gestionssanctionseps58', 'action' => 'traitement' ) ),
				'Visualisation des sanctions' => array( 'url' => array( 'controller' => 'gestionssanctionseps58', 'action' => 'visualisation' ) ),
			),
		),
		'CER' => array(
			'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
			'1. Affectation d\'un référent' => array(
				'Référents à affecter' => array( 'url' => array( 'controller' => 'cohortesreferents93', 'action' => 'affecter'  ) ),
				'Référents déjà affectés' => array( 'url' => array( 'controller' => 'cohortesreferents93', 'action' => 'affectes'  ) ),
			),
			'2. Saisie d\'un CER' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'saisie'  ) ),
			'3. Validation CPDV' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'avalidercpdv'  ) ),
			'4. Décision CG' => array(
				'4.1 Première lecture' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'premierelecture'  ) ),
				'4.2 Validation CS' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'validationcs'  ) ),
				'4.3 Validation Cadre' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'validationcadre'  ) ),
			),
			'5. Tableau de suivi' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'visualisation'  ) ),
		),
		'Tableaux de bord' => array(
			'Indicateurs mensuels' => array( 'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) ),
			'Statistiques ministérielles' => array(
				'Indicateurs d\'orientations' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrientations'  ) ),
				'Indicateurs d\'organismes' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrganismes'  ) ),
				'Indicateurs de nature de contrats' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursDelais'  ) ),
				'Indicateurs de caractéristiques de contrats' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursCaracteristiquesContrats'  ) ),
				'Indicateurs de réorientations' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursReorientations'  ) ),
				'Indicateurs de motifs de réorientations' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursMotifsReorientation'  ) ),
			),
			'Indicateurs de suivi' => array( 'url' => array( 'controller' => 'indicateurssuivis', 'action' => 'index' ) ),
		),
		'Administration' => array(
			'Paramétrages' => array( 'url' => array( 'controller' => 'parametrages', 'action' => 'index'  ) ),
			'Paiement allocation' => array(
				'Listes nominatives' => array( 'url' => array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ) ),
				'Mandats mensuels' => array( 'url' => array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ) ),
			),
			'Gestion des anomalies' => array(
				'Doublons simples' => array( 'url' => array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'index'  ) ),
			),
		),
		'Visionneuse' => array(
			'logs' => array( 'url' => array( 'controller' => 'visionneuses', 'action' => 'index' ) ),
		),
		'Déconnexion '.$this->Session->read( 'Auth.User.username' ) => array(
			'url' => array( 'controller' => 'users', 'action' => 'logout' )
		)
	);

	echo $this->Menu->make2( $items, 'a' );
?>
	</div>
</div>
<?php endif;?>
<?php if( Configure::read( 'debug' ) > 0 ): ?>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<hr/>

<div id="menu1Wrapper">
	<div class="menu1">
		<ul>
		<?php if( $this->Session->check( 'Auth.User' ) ): ?>
			<?php if(
					$this->Permissions->check( 'cohortes', 'nouvelles' )
					|| $this->Permissions->check( 'cohortes', 'orientees' )
					|| $this->Permissions->check( 'cohortes', 'enattente' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'validees' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' )
					|| $this->Permissions->check( 'cohortesci', 'nouveaux' )
					|| $this->Permissions->check( 'cohortesci', 'valides' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxsimple' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' )
					|| $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'oriente' )
					|| $this->Permissions->check( 'cohortespdos', 'avisdemande' )
					|| $this->Permissions->check( 'cohortespdos', 'valide' )
					|| $this->Permissions->check( 'cohortespdos', 'enattente' )
					|| $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' )
					|| $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' )
					|| $this->Permissions->check( 'nonorientationsproseps', 'index' )
					|| $this->Permissions->check( 'nonorientationsproseps', 'selectionradies' )
				):?>
				<li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
					<?php
						if( Configure::read( 'Cg.departement' ) == 66 ) {
							echo $this->Xhtml->link( 'Gestion de listes', '#' );
						}
						else {
							echo $this->Xhtml->link( 'Cohortes', '#' );
						}
					?>
					<ul>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'transfert' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'validees' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' ) ) ):?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'APRE ', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'À valider', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'apresavalider' ), array( 'title' => 'À valider' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'validees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'À notifier', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'validees' ), array( 'title' => 'À notifier' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Notifiées', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'notifiees' ), array( 'title' => 'Notifiées' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'transfert' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Transfert cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'transfert' ), array( 'title' => 'Transfert cellule' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Traitement cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'traitement' ), array( 'title' => 'Traitement cellule' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( in_array( Configure::read( 'Cg.departement' ), array( 66, 93 ) ) && ( $this->Permissions->check( 'cohortesci', 'nouveaux' ) || $this->Permissions->check( 'cohortesci', 'valides' ) || $this->Permissions->check( 'cohortesci', 'nouveauxsimple' ) || $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' ) ) ) :?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'CER ', '#' );?>
									<ul>
										<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveauxsimple' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats Simples à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxsimple' ), array( 'title' => 'Contrats Simples à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats Particuliers à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxparticulier' ), array( 'title' => 'Contrats Particuliers à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Décisions prises', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Décisions prises' ) );?></li>
											<?php endif; ?>
										<?php elseif( Configure::read( 'Cg.departement' ) == 93 ):?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveaux' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveaux' ), array( 'title' => 'Contrats à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats validés', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Contrats validés' ) );?></li>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) || $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ) ):?>
							<!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Fiches de candidature ', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Fiches en attente', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesenattente' ), array( 'title' => 'Fiches en attente' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Fiches en cours', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesencours' ), array( 'title' => 'Fiches en cours' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $this->Xhtml->link( 'Dossiers PCGs ', '#' );?>
                                    <ul>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers en attente d\'affectation', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'enattenteaffectation' ), array( 'title' => 'Dossiers en attente d\'affectation' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers affectés', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'affectes' ), array( 'title' => 'Dossiers affectés' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers à imprimer', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'aimprimer' ), array( 'title' => 'Dossiers à imprimer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers à transmettre', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'atransmettre' ), array( 'title' => 'Dossiers à transmettre' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
                        <?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' ) || $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' ) || $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) || $this->Permissions->check( 'cohortesnonorientes66', 'oriente' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Non orientés 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $this->Xhtml->link( 'Non orientation ', '#' );?>
                                    <ul>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'isemploi' ), array( 'title' => 'Inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Non inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploiaimprimer' ), array( 'title' => 'Non inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Gestion des réponses', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploi' ), array( 'title' => 'Gestion des réponses' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notifaenvoyer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Notifications à envoyer', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notifaenvoyer' ), array( 'title' => 'Notifications à envoyer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'oriente' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Orientés et notifiés', array( 'controller' => 'cohortesnonorientes66', 'action' => 'oriente' ), array( 'title' => 'Orientés et notifiés' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
						<?php if( $this->Permissions->check( 'cohortes', 'nouvelles' )
								|| $this->Permissions->check( 'cohortes', 'orientees' )
								|| $this->Permissions->check( 'cohortes', 'enattente' )
								/*|| $this->Permissions->check( 'cohortes', 'preconisationscalculables' )
								|| $this->Permissions->check( 'cohortes', 'preconisationsnoncalculables' )
								|| $this->Permissions->check( 'cohortes', 'statistiques' )*/ ): ?>
							<!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Orientation', '#' );?>
									<ul>
										<?php /*if( $this->Permissions->check( 'cohortes', 'statistiques' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Statistiques', array( 'controller' => 'cohortes', 'action' => 'statistiques' ), array( 'title'=>'Statistiques' ) );?></li>
										<?php endif; */ ?>
										<?php if( $this->Permissions->check( 'cohortes', 'nouvelles' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes non orientées', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array( 'title'=>'Demandes non orientées' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortes', 'enattente' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes en attente de validation d\'orientation', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array( 'title'=>'Demandes en attente de validation d\'orientation' ) );?></li>
										<?php endif; ?>
										<?php /*if( $this->Permissions->check( 'cohortes', 'preconisationscalculables' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes d\'orientation préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationscalculables' ), array( 'title'=>'Demandes à orienter, possédant une préconisation' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortes', 'preconisationsnoncalculables' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes d\'orientation non préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationsnoncalculables' ), array( 'title'=>'Demandes à orienter, ne possédant pas de préconisation' ) );?></li>
										<?php endif; */?>
										<?php if( $this->Permissions->check( 'cohortes', 'orientees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array( 'title'=>'Demandes orientées' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( $this->Permissions->check( 'cohortespdos', 'avisdemande' ) || $this->Permissions->check( 'cohortespdos', 'valide' ) || $this->Permissions->check( 'cohortespdos', 'enattente' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
							<!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'PDOs', '#' );?>
								<ul>
									<?php if( $this->Permissions->check( 'cohortespdos', 'avisdemande' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Nouvelles demandes', array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ), array( 'title' => 'Avis CG demandé' ) );?></li>
									<?php endif; ?>
									<?php if( $this->Permissions->check( 'cohortespdos', 'valide' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Liste PDOs', array( 'controller' => 'cohortespdos', 'action' => 'valide' ), array( 'title' => 'PDOs validés' ) );?></li>
									<?php endif; ?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( ( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) || $this->Permissions->check( 'nonorientationsproseps', 'index' ) || $this->Permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
                            <!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $this->Xhtml->link( 'EPs', '#' );?>
                                <ul>
								<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $this->Xhtml->link( 'Relances (EP)','#' );?>
										<ul>
											<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) ): ?>
												<li><?php echo $this->Xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'cohorte' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte' ) ) );?></li>
											<?php endif;?>
											<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
												<li><?php echo $this->Xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impressions' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions' ) ) );?></li>
											<?php endif;?>
										</ul>
									</li>
								<?php endif; ?>
                                <?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $this->Xhtml->link( 'Parcours social sans réorientation', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?>
									</li>
                                <?php endif; ?>
                                <?php if( $this->Permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ): ?>
                                    <li> <?php echo $this->Xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'nonrespectssanctionseps93', 'action' => 'selectionradies'  ) );?> </li>
                                <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( $this->Permissions->check( 'dossiers', 'index' ) || $this->Permissions->check( 'criteres', 'index' ) || $this->Permissions->check( 'criteresci', 'index' ) ) :?>
				<li id="menu2one" >
					<?php echo $this->Xhtml->link( 'Recherches', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'dossiers', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteres', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ): ?>
								<li><?php echo $this->Xhtml->link( 'Par APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteresci', 'index' ) || $this->Permissions->check( 'criterescuis', 'index' ) ):?>
							<li>
								<?php echo $this->Xhtml->link( 'Par Contrats', '#' );?>
								<ul>
									<li><?php echo $this->Xhtml->link( 'Par CER',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
									<li><?php echo $this->Xhtml->link( 'Par CUI',  array( 'controller' => 'criterescuis', 'action' => 'index'  ) );?></li>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteresentretiens', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par Entretiens', array( 'controller' => 'criteresentretiens', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

                        <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                            <?php if( $this->Permissions->check( 'criteresfichescandidature', 'index' ) ): ?>
                                <li><?php echo $this->Xhtml->link( 'Par Fiches de candidature', array( 'controller' => 'criteresfichescandidature', 'action' => 'index' ) );?>
                                </li>
                            <?php endif;?>
                        <?php endif;?>

						<?php if( $this->Permissions->check( 'cohortesindus', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par Indus', array( 'controller' => 'cohortesindus', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'dsps', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par DSPs', array( 'controller' => 'dsps', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'criteresrdv', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par Rendez-vous',  array( 'controller' => 'criteresrdv', 'action' => 'index'  ) );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'dossier' ) || $this->Permissions->check( 'criterestraitementspcgs66', 'index' ) || $this->Permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Par Dossiers PCGs', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'dossier' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Dossiers PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'dossier'  ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criterestraitementspcgs66', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Traitements PCGs',  array( 'controller' => 'criterestraitementspcgs66', 'action' => 'index'  ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Gestionnaires PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'gestionnaire'  ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						<?php else:?>
							<?php if( $this->Permissions->check( 'criterespdos', 'index' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Par PDOs', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Nouvelles PDOs',  array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Liste des PDOs',  array( 'controller' => 'criterespdos', 'action' => 'index'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
							<?php if( $this->Permissions->check( 'criteresdossierscovs58', 'index' ) ):?>
								<li> <?php echo $this->Xhtml->link( 'Par Dossiers COV', array( 'controller' => 'criteresdossierscovs58', 'action' => 'index'  ) );?> </li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'sanctionseps58', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Radiation de Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionradies' ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Non inscription à Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionnoninscrits' ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Demande de maintien dans le social', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == '66' ): ?>
							<?php if( $this->Permissions->check( 'criteresbilansparcours66', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Par Bilans de parcours',  array( 'controller' => 'criteresbilansparcours66', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<!-- TODO : à faire !!! -->
							<?php if( $this->Permissions->check( 'defautsinsertionseps66', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Non inscrits au Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionnoninscrits'  ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionradies'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Demande de maintien dans le social',  array( 'controller' => 'nonorientationsproseps', 'action' => 'index'  ) );?></li>
							<?php endif;?>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( ( Configure::read( 'Cg.departement' ) == 93 )  && ( $this->Permissions->check( 'criteresapres', 'index' ) || $this->Permissions->check( 'repsddtefp', 'index' ) || $this->Permissions->check( 'comitesapres', 'index' ) || $this->Permissions->check( 'recoursapres', 'index' ) ) ):?>
				<li id="menu3one" >
					<?php echo $this->Xhtml->link( 'APRE', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Liste des demandes d\'APRE', '#');?>
									<ul>
										<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Toutes les APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criteresapres', 'index' ) && ( Configure::read( 'Cg.departement' ) != 66 ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Eligibilité des APREs', array( 'controller' => 'criteresapres', 'action' => 'eligible' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demande de recours', array( 'controller' => 'recoursapres', 'action' => 'demande' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Visualisation des recours', array( 'controller' => 'recoursapres', 'action' => 'visualisation' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
						<?php endif;?>

				<?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?> <!-- Début de l'affichage en fonction du CG-->
						<?php if( $this->Permissions->check( 'comitesapres', 'index' ) || $this->Permissions->check( 'cohortescomitesapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Comité d\'examen', '#');?>
								<ul>
									<?php if( $this->Permissions->check( 'comitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Recherche de Comité', array( 'controller' => 'comitesapres', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Gestion des décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Notifications décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'comitesapres', 'liste' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Liste des Comités', array( 'controller' => 'comitesapres', 'action' => 'liste' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'repsddtefp', 'index' ) || $this->Permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Reporting bi-mensuel', '#' );?>
								<ul>
									<?php if( $this->Permissions->check( 'repsddtefp', 'index' ) ):?>
										<li><?php echo $this->Xhtml->link( 'Reporting bi-mensuel DDTEFP', array( 'controller' => 'repsddtefp', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
										<li><?php echo $this->Xhtml->link( 'Suivi et contrôle de l\'enveloppe APRE', array( 'controller' => 'repsddtefp', 'action' => 'suivicontrole' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'integrationfichiersapre', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Journal d\'intégration des fichiers CSV', array( 'controller' => 'integrationfichiersapre', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'etatsliquidatifs', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'États liquidatifs APRE', array( 'controller' => 'etatsliquidatifs', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'budgetsapres', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Budgets APRE', array( 'controller' => 'budgetsapres', 'action' => 'index' ) );?></li>
						<?php endif;?>

				<?php endif;?> <!-- Fin de l'affichage en fonction du CG-->

					</ul>
				</li>
			<?php endif;?>

			<!-- Menu de gestion de la COV pour le cg 58-->
			<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
				<?php if( $this->Permissions->check( 'covs58', 'index' ) ): ?>
					<li id="menu8one">
						<?php echo $this->Xhtml->link( 'COV', array( 'controller' => 'covs58', 'action' => 'index' ) ); ?>
					</li>
				<?php endif; ?>
			<?php endif;?>

			<!-- Début du menu des maquettes offre d'insertion-->
			<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
					<?php if( $this->Permissions->check( 'offresinsertion', 'index' ) ): ?>
					<li id="menu10one">
						<?php echo $this->Xhtml->link( 'Offre d\'Insertion', array( 'controller' => 'offresinsertion', 'action' => 'index' ) ); ?>
					</li>
				<?php endif; ?>
			<?php endif;?>
			<?php if( Configure::read( 'Cg.departement' ) == 34 ):?>
				<li id="menuTest0one" >
					<?php echo $this->Xhtml->link( 'Offre d\'Insertion', '#' );?>
					<ul>
						<li>
							<?php echo $this->Xhtml->link( 'Appels à projet', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'candidature_appel_a_projet' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Liste des appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_appels_a_projet' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Candidatures', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie de candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'saisie_candidature_structure1' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Liste des candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_candidats' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Analyses des candidatures', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Par lot', array( 'controller' => 'pages/display/webrsa/', 'action' => 'analyse_candidature' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Par structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_structure_candidate' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Par actions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'selection_actions' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Administration structure', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Recherche de structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_admin_structure' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Suivi des étapes / pièces', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_etapes_pieces' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Conventions', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Liste des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Création de convention', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Gestion des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Paiements', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie déclenchements paiement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Suivi des paiements', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_suivi_paiement' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Demande de remboursement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'demande_remboursement' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Offres', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Recherche d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_offre' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Création d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_offre' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Gestion des offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_offre' ) );?></li>
							</ul>
						</li>
							<li><?php echo $this->Xhtml->link( 'Suivi des stagiaires', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_stagiaires' ) );?></li>
					</ul>
				</li>
			<?php endif;?>
			<!-- Fin du menu des maquettes offre d'insertion-->

			<!-- Début du Nouveau menu pour les Equipes pluridisciplinaires -->

			<?php if( $this->Permissions->check( 'eps', 'liste' ) ) :?>
			<li id="menu4one">
				<?php echo $this->Xhtml->link( 'Eq. Pluri.', '#' );?>
				<ul>
					<li>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
							<a href="#">1. Gestion des EPs</a>
						<?php else:?>
							<a href="#">1. Mise en place du dispositif</a>
						<?php endif;?>
						<ul>
							<?php if( Configure::read( 'Cg.departement' ) == 66 && $this->Permissions->check( 'defautsinsertionseps66', 'courriersinformations' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Courriers d\'information avant EPL Audition',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'courriersinformations'  ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'membreseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des membres', array( 'controller' => 'membreseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'eps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des EPs', array( 'controller' => 'eps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'commissionseps', 'add' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des Commissions', array( 'controller' => 'commissionseps', 'action' => 'add' ) );?></li>
							<?php endif;?>
						</ul>
					</li>
					<?php if( $this->Permissions->check( 'commissionseps', 'recherche' ) ):?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
								<?php echo $this->Xhtml->link( '2. Recherche de commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php else:?>
								<?php echo $this->Xhtml->link( '2. Constitution de la commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php endif;?>
						</li>
					<?php endif;?>
					<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
						<li><?php echo $this->Xhtml->link( '3. Arbitrage EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
					<?php else: ?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
								<a href="#">3. Avis/Décisions</a>
							<?php else:?>
								<a href="#">3. Arbitrage</a>
							<?php endif;?>

							<ul>
                                <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                                    <li><?php echo $this->Xhtml->link( 'Avis EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $this->Xhtml->link( 'Décisions CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
                                <?php else: ?>
                                    <li><?php echo $this->Xhtml->link( 'EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $this->Xhtml->link( 'CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
					<li><?php echo $this->Xhtml->link( '4. Consultation et impression des décisions', array( 'controller' => 'commissionseps', 'action' => 'decisions' ) );?></li>

					<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
						<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'traitement' ) || $this->Permissions->check( 'gestionssanctionseps58', 'visualisation' ) ):?>
						<li>
							<?php echo $this->Xhtml->link( '5. Gestion des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => '#' ) );?>
							<ul>
								<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'traitement' ) ):?>
									<li>
										<?php echo $this->Xhtml->link( 'Gestion des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => 'traitement' ) );?>
									</li>
								<?php endif;?>
								<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'visualisation' ) ):?>
									<li>
										<?php echo $this->Xhtml->link( 'Visualisation des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => 'visualisation' ) );?>
									</li>
								<?php endif;?>
							</ul>
						</li>
						<?php endif;?>
					<?php endif;?>
				</ul>
			</li>
			<?php endif;?>
			<!-- Fin du Nouveau menu pour les Equipes pluridisciplinaires -->

			<!-- Début workflow CER 93 -->
			<?php if( Configure::read( 'Cg.departement' ) == 93 && ( $this->Permissions->check( 'cohortesreferents93', 'affecter' ) || $this->Permissions->check( 'cohortesreferents93', 'affectes' ) || $this->Permissions->check( 'cohortescers93', 'saisie' ) || $this->Permissions->check( 'cohortescers93', 'avalidercpdv' ) || $this->Permissions->check( 'cohortescers93', 'premierelecture' ) || $this->Permissions->check( 'cohortescers93', 'validationcs' ) || $this->Permissions->check( 'cohortescers93', 'validationcadre' ) || $this->Permissions->check( 'cohortescers93', 'visualisation' ) ) ) :?>

			<li id="menu4one">
				<?php echo $this->Xhtml->link( 'CER', '#' );?>
				<ul>
					<?php if( $this->Permissions->check( 'cohortesreferents93', 'affecter' ) || $this->Permissions->check( 'cohortesreferents93', 'affectes' ) ):?>
					<li>
						<?php echo $this->Xhtml->link( '1. Affectation d\'un référent', '#' );?>
						<ul>
							<li><?php echo $this->Xhtml->link( 'Référents à affecter',  array( 'controller' => 'cohortesreferents93', 'action' => 'affecter'  ) );?></li>
							<li><?php echo $this->Xhtml->link( 'Référents déjà affectés',  array( 'controller' => 'cohortesreferents93', 'action' => 'affectes'  ) );?></li>
						</ul>
					</li>
					<?php endif;?>
					<?php if( $this->Permissions->check( 'cohortescers93', 'saisie' ) ):?>
					<li>
						<?php echo $this->Xhtml->link( '2. Saisie d\'un CER',  array( 'controller' => 'cohortescers93', 'action' => 'saisie'  ) );?>
					</li>
					<?php endif;?>
					<?php if( $this->Permissions->check( 'cohortescers93', 'avalidercpdv' ) ):?>
					<li>
						<?php echo $this->Xhtml->link( '3. Validation CPDV',  array( 'controller' => 'cohortescers93', 'action' => 'avalidercpdv'  ) );?>
					</li>
					<?php endif;?>
					<?php if( $this->Permissions->check( 'cohortescers93', 'premierelecture' ) || $this->Permissions->check( 'cohortescers93', 'validationcs' ) || $this->Permissions->check( 'cohortescers93', 'validationcadre' ) ):?>
					<li>
						<?php echo $this->Xhtml->link( '4. Décision CG', '#' );?>
						<ul>
							<?php if( $this->Permissions->check( 'cohortescers93', 'premierelecture' ) ):?>
							<li><?php echo $this->Xhtml->link( '4.1 Première lecture',  array( 'controller' => 'cohortescers93', 'action' => 'premierelecture'  ) );?></li><?php endif;?>
							<?php if( $this->Permissions->check( 'cohortescers93', 'validationcs' ) ):?>
							<li><?php echo $this->Xhtml->link( '4.2 Validation CS',  array( 'controller' => 'cohortescers93', 'action' => 'validationcs'  ) );?></li><?php endif;?>
							<?php if( $this->Permissions->check( 'cohortescers93', 'validationcadre' ) ):?>
							<li><?php echo $this->Xhtml->link( '4.3 Validation Cadre',  array( 'controller' => 'cohortescers93', 'action' => 'validationcadre'  ) );?></li><?php endif;?>
						</ul>
					</li>
					<?php endif;?>
					<?php if( $this->Permissions->check( 'cohortescers93', 'visualisation' ) ):?>
					<li>
						<?php echo $this->Xhtml->link( '5. Tableau de suivi',  array( 'controller' => 'cohortescers93', 'action' => 'visualisation'  ) );?>
					</li>
					<?php endif;?>
				</ul>
			</li>
			<?php endif;?>
			<!-- Fin workflow CER 93 -->

			<?php if( $this->Permissions->check( 'indicateursmensuels', 'index' ) || $this->Permissions->check( 'statistiquesministerielles', '#' ) ) :?>
				<li id="menu5one" >
					<?php echo $this->Xhtml->link( 'Tableaux de bord', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'indicateursmensuels', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Indicateurs mensuels', array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) );?></li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'statistiquesministerielles', 'indicateursOrientations' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursOrganismes' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursNatureContrats' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursCaracteristiquesContrats' ) ):?>
							<li>
								<?php echo $this->Xhtml->link( 'Statistiques ministérielles', '#' );?>
								<ul>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs d\'orientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrientations'  ) );?>
									</li>

									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs d\'organismes',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrganismes'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de nature de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursDelais'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de caractéristiques de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursCaracteristiquesContrats'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursReorientations'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de motifs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursMotifsReorientation'  ) );?>
									</li>
								</ul>
							</li>

							<?php if( $this->Permissions->check( 'indicateurssuivis', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Indicateurs de suivi', array( 'controller' => 'indicateurssuivis', 'action' => 'index' ) );?></li>
							<?php endif;?>

						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>

			<?php if( $this->Permissions->check( 'parametrages', 'index' ) || $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) || $this->Permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
					<li id="menu6one">
						<?php echo $this->Xhtml->link( 'Administration', '#' );?>
						<ul>
							<?php if( $this->Permissions->check( 'parametrages', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Paramétrages',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) || $this->Permissions->check( 'totalisationsacomptes', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Paiement allocation', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Listes nominatives', array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ), array( 'title' => 'Listes nominatives' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'totalisationsacomptes', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Mandats mensuels', array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ), array( 'title' => 'Mandats mensuels' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Gestion des anomalies', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Doublons simples',  array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'index'  ), array( 'title' => 'Gestion des anomalies de doublons simples au sein d\'un foyer donné' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						</ul>
					</li>
			<?php endif;?>

			<?php if( $this->Permissions->check( 'visionneuses', 'index' ) ) :?>
				<li id="menu7one" >
					<?php echo $this->Xhtml->link( 'Visionneuse', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'visionneuses', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'logs', array( 'controller' => 'visionneuses', 'action' => 'index' ) );?></li>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>

			<li id="menu9one"><?php echo $this->Xhtml->link( 'Déconnexion '.$this->Session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
			<?php else: ?>
				<li><?php echo $this->Xhtml->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>
<?php endif;?>