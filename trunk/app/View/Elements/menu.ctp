<?php if( $this->Session->check( 'Auth.User' ) ): ?>
<div id="menu1Wrapper">
	<div class="menu1">
<?php
	// INFO: réécriture des clés -> ajouter un espace en début ou fin par exemple
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
				'Demandes non orientées' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'nouvelles' ) ),
				'Demandes en attente de validation d\'orientation' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'enattente' ) ),
				'Demandes orientées' => array( 'url' => array( 'controller' => 'cohortes', 'action' => 'orientees' ) ),
			),
			'PDOs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'Nouvelles demandes' => array(
					'url' => array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ),
					'title' => 'Avis CG demandé',
				),
				'Liste PDOs' => array(
					'url' => array( 'controller' => 'cohortespdos', 'action' => 'valide' ),
					'title' => 'PDOs validés',
				),
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
			'Transferts PDV' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'Allocataires à transférer' => array( 'url' => array( 'controller' => 'cohortestransfertspdvs93', 'action' => 'atransferer' ) ),
				'Allocataires transférés' => array( 'url' => array( 'controller' => 'cohortestransfertspdvs93', 'action' => 'transferes' ) ),
			),
            'Clôture référents' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
                'url' => array( 'controller' => 'referents', 'action' => 'clotureenmasse' )
			),
            __d( 'cohortesd2pdvs93', '/Cohortesd2pdvs93/index/:heading' ) => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
                'url' => array( 'controller' => 'cohortesd2pdvs93', 'action' => 'index' )
			),
            __d( 'cohortesrendezvous', '/Cohortesrendezvous/cohorte/:heading' ) => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
                'url' => array( 'controller' => 'cohortesrendezvous', 'action' => 'cohorte' )
			),
		),
		'Recherches' => array(
			'Par dossier / allocataire' => array( 'url' => array( 'controller' => 'dossiers', 'action' => 'index' ) ),
			'Par dossier / allocataire (nouveau)' => array( 'url' => array( 'controller' => 'dossiers', 'action' => 'search' ) ),
			'Par Orientation' => array( 'url' => array( 'controller' => 'criteres', 'action' => 'index' ) ),
			'Par Orientation (nouveau)' => array( 'url' => array( 'controller' => 'orientsstructs', 'action' => 'search' ) ),
			'Par APREs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresapres', 'action' => 'all' )
			),
			'Par APREs (nouveau)' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'apres', 'action' => 'search' )
			),
			'Par Contrats' => array(
				'Par CER' => array( 'url' => array( 'controller' => 'criteresci', 'action' => 'index'  ) ),
				'Par CER (nouveau)' => array( 'url' => array( 'controller' => 'contratsinsertion', 'action' => 'search'  ) ),
				'Par CUI' => array(
					'url' => array( 'controller' => 'criterescuis', 'action' => 'search'  ),
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 )
				),
				'Par CUI (nouveau)' => array(
					'url' => array( 'controller' => 'cuis', 'action' => 'search'  ),
					'disabled' => ( Configure::read( 'Cg.departement' ) != 66 )
				),
			),
			'Par Entretiens' => array( 'url' => array( 'controller' => 'criteresentretiens', 'action' => 'index' ) ),
			'Par Entretiens (nouveau)' => array( 'url' => array( 'controller' => 'entretiens', 'action' => 'search' ) ),
			'Par Fiches de candidature' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresfichescandidature', 'action' => 'index' )
			),
			'Par Fiches de candidature (nouveau)' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'actionscandidats_personnes', 'action' => 'search' )
			),
			'Par Indus' => array( 'url' => array( 'controller' => 'cohortesindus', 'action' => 'index' ) ),
			'Par Indus (nouveau)' => array( 'url' => array( 'controller' => 'indus', 'action' => 'search' ) ),
			'Par DSPs' => array( 'url' => array( 'controller' => 'dsps', 'action' => 'index' ) ),
			'Par DSPs (nouveau)' => array( 'url' => array( 'controller' => 'dsps', 'action' => 'search' ) ),
			'Par Rendez-vous' => array( 'url' => array( 'controller' => 'criteresrdv', 'action' => 'index'  ) ),
			'Par Rendez-vous (nouveau)' => array( 'url' => array( 'controller' => 'rendezvous', 'action' => 'search'  ) ),
			'Par Dossiers PCGs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Dossiers PCGs' => array( 'url' => array( 'controller' => 'criteresdossierspcgs66', 'action' => 'dossier'  ) ),
				'Dossiers PCGs (nouveau)' => array( 'url' => array( 'controller' => 'dossierspcgs66', 'action' => 'search'  ) ),
				'Traitements PCGs' => array( 'url' => array( 'controller' => 'criterestraitementspcgs66', 'action' => 'index'  ) ),
				'Traitements PCGs (nouveau)' => array( 'url' => array( 'controller' => 'traitementspcgs66', 'action' => 'search'  ) ),
				'Gestionnaires PCGs' => array( 'url' => array( 'controller' => 'criteresdossierspcgs66', 'action' => 'gestionnaire'  ) ),
				'Gestionnaires PCGs (nouveau)' => array( 'url' => array( 'controller' => 'dossierspcgs66', 'action' => 'search_gestionnaire'  ) ),
			),
			'Par PDOs' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) == 66 ),
				'Nouvelles PDOs' => array( 'url' => array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) ),
				'Liste des PDOs' => array( 'url' => array( 'controller' => 'criterespdos', 'action' => 'index'  ) ),
			),
			'Par Dossiers COV' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
				'url' => array( 'controller' => 'criteresdossierscovs58', 'action' => 'index'  ),
				__d( 'nonorientationsproscovs58', '/Nonorientationsproscovs58/cohorte/:heading' ) => array( 'url' => array( 'controller' => 'nonorientationsproscovs58', 'action' => 'cohorte' ) ),
			),
			'Par Dossiers EP' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 58 ),
				'Radiation de Pôle Emploi' => array( 'url' => array( 'controller' => 'sanctionseps58', 'action' => 'selectionradies' ) ),
				'Non inscription à Pôle Emploi' => array( 'url' => array( 'controller' => 'sanctionseps58', 'action' => 'selectionnoninscrits' ) ),
			),
			'Par Bilans de parcours' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'criteresbilansparcours66', 'action' => 'index'  ),
			),
			'Par Bilans de parcours (nouveau)' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'bilansparcours66', 'action' => 'search'  ),
			),
			'Pôle Emploi' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Non inscrits au Pôle Emploi' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionnoninscrits'  ) ),
				'Non inscrits au Pôle Emploi (nouveau)' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'search_noninscrits'  ) ),
				'Radiés de Pôle Emploi' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionradies'  ) ),
				'Radiés de Pôle Emploi (nouveau)' => array( 'url' => array( 'controller' => 'defautsinsertionseps66', 'action' => 'search_radies'  ) ),
			),
			'Demande de maintien dans le social' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'nonorientationsproseps', 'action' => 'index'  )
			),
			'Demande de maintien dans le social (nouveau)' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'url' => array( 'controller' => 'nonorientationsproseps', 'action' => 'search'  )
			),
			'Par allocataires sortants' => array(
				'Intra-département' => array(
					'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
					'url' => array( 'controller' => 'criterestransfertspdvs93', 'action' => 'index'  )
				),
				'Hors département' => array(
					'url' => array( 'controller' => 'demenagementshorsdpts', 'action' => 'search1'  )
				),
				'Hors département (nouveau)' => array(
					'url' => array( 'controller' => 'demenagementshorsdpts', 'action' => 'search'  )
				),
			),
			'Par fiches de prescription' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				'url' => array( 'controller' => 'fichesprescriptions93', 'action' => 'search'  )
			),
		),
		'APRE' => array(
			'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
			'Liste des demandes d\'APRE' => array(
				'Toutes les APREs' => array( 'url' => array( 'controller' => 'criteresapres', 'action' => 'all' ) ),
				'Toutes les APREs (nouveau)' => array( 'url' => array( 'controller' => 'apres', 'action' => 'search' ) ),
				'Eligibilité des APREs' => array( 'url' => array( 'controller' => 'criteresapres', 'action' => 'eligible' ) ),
				'Eligibilité des APREs (nouveau)' => array( 'url' => array( 'controller' => 'apres', 'action' => 'search_eligibilite' ) ),
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
			'Paramétrages' => array(
				'Création des partenaires' => array(
					'url' => array( 'controller' => 'partenaires', 'action' => 'index' )
				),
				'Création des contacts' => array(
					'url' => array( 'controller' => 'contactspartenaires', 'action' => 'index' )
				),
				'Création des actions' => array(
					'url' => array( 'controller' => 'actionscandidats', 'action' => 'index' )
				),
                'Création des programmes région' => array(
					'url' => array( 'controller' => 'progsfichescandidatures66', 'action' => 'index' )
				),
				'Motifs de sortie' => array(
					'url' => array( 'controller' => 'motifssortie', 'action' => 'index' )
				)
			),
			'Tableau global' => array(
				'url' => array( 'controller' => 'offresinsertion', 'action' => 'index' )
			)
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
				'disabled' => !in_array( Configure::read( 'Cg.departement' ), array( 66, 93 ) ),
				( Configure::read( 'Cg.departement' ) == 66 ? 'Avis EP' : 'EP' ) => array(
					'url' => array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ),
				),
				( Configure::read( 'Cg.departement' ) == 66 ? 'Décisions CG' : 'CG' ) => array(
					'url' => array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ),
				),
			),
			( Configure::read( 'Cg.departement' ) == 66 ? '4. Consultation et impression des avis et décisions' : '4. Consultation et impression des décisions' ) => array(
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
			'1. Affectation d\'un référent' => array( 'url' => array( 'controller' => 'cohortesreferents93', 'action' => 'affecter'  ) ),
			'2. Saisie d\'un CER' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'saisie'  ) ),
			'3. Validation Responsable' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'avalidercpdv'  ) ),
			'4. Décision CG' => array(
				'4.1 Première lecture' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'premierelecture'  ) ),
				'4.2 Validation CS' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'validationcs'  ) ),
				'4.3 Validation Cadre' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'validationcadre'  ) ),
			),
			'5. Tableau de suivi' => array( 'url' => array( 'controller' => 'cohortescers93', 'action' => 'visualisation'  ) ),
		),
		'Tableaux de bord' => array(
			'Indicateurs mensuels' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 66 ),
				'Généralités' => array( 'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) ),
				'Nombre d\'allocataires' => array( 'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'nombre_allocataires' ) ),
				'Les orientations' => array( 'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'orientations' ) ),
				'Les CER' => array( 'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'contratsinsertion' ) ),
			),
			'Indicateurs mensuels ' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) == 66 ),
				'url' => array( 'controller' => 'indicateursmensuels', 'action' => 'index' ),
			),
			'Statistiques ministérielles' => array(
				'Indicateurs d\'orientations' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_orientations'  ) ),
				'Indicateurs d\'organismes' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_organismes'  ) ),
				'Indicateurs de délais' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_delais'  ) ),
				'Indicateurs de réorientations' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_reorientations'  ) ),
				'Indicateurs de motifs de réorientation' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_motifs_reorientation'  ) ),
				'Indicateurs de caractéristiques de contrats' => array( 'url' => array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateurs_caracteristiques_contrats'  ) ),
			),
			'Indicateurs de suivi' => array( 'url' => array( 'controller' => 'indicateurssuivis', 'action' => 'search' ) ),
			'Tableaux de suivi d\'activité' => array(
				'disabled' => ( Configure::read( 'Cg.departement' ) != 93 ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/index/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'index' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableaud1/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableaud1' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableaud2/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableaud2' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableau1b3/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableau1b3' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableau1b4/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableau1b4' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableau1b5/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableau1b5' ) ),
				__d( 'tableauxsuivispdvs93', '/Tableauxsuivispdvs93/tableau1b6/:heading' ) => array( 'url' => array( 'controller' => 'tableauxsuivispdvs93', 'action' => 'tableau1b6' ) ),
			),
		),
		'Administration' => array(
			'Paramétrages' => array( 'url' => array( 'controller' => 'parametrages', 'action' => 'index'  ) ),
			'Paiement allocation' => array(
				'Listes nominatives' => array( 'url' => array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ) ),
				'Mandats mensuels' => array( 'url' => array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ) ),
			),
			'Gestion des anomalies' => array(
				'Doublons simples' => array(
					'url' => array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'index'  ),
					'title' => 'Gestion des anomalies de doublons simples au sein d\'un foyer donné',
				),
				'Doublons complexes' => array(
					'url' => array( 'controller' => 'gestionsdoublons', 'action' => 'index'  ),
					'title' => 'Gestion des anomalies de doublons complexes au sein de foyers différents',
				),
			),
			__d( 'droit', 'Dossierseps:administration' ) => array(
				'url' => array( 'controller' => 'dossierseps', 'action' => 'administration'  ),
			),
			'Habilitations' => array(
				'Groupes' => array(
					'url' => array( 'controller' => 'groups', 'action' => 'index' ),
					'title' => 'Gestion des groupes d\'utilisateurs',
				),
				'Utilisateurs' => array(
					'url' => array( 'controller' => 'users', 'action' => 'index' ),
					'title' => 'Gestion des utilisateurs',
				),
			),
			'Vérification de l\'application' => array(
				'url' => array( 'controller' => 'checks', 'action' => 'index' ),
			),
			'Visionneuse' => array(
				'logs' => array( 'url' => array( 'controller' => 'visionneuses', 'action' => 'index' ) ),
			),
		),
		'Déconnexion '.$this->Session->read( 'Auth.User.username' ) => array(
			'url' => array( 'controller' => 'users', 'action' => 'logout' )
		)
	);

	echo $this->Menu->make2( $items, 'a' );
?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$$( '#menu1Wrapper li.branch' ).each(
	function( elmt ) {
		$(elmt).observe( 'mouseover', function() { $(this).addClassName( 'hover' ); } );
		$(elmt).observe( 'mouseout', function() { $(this).removeClassName( 'hover' ); } );
    }
);
//]]>
</script>
<?php endif;?>