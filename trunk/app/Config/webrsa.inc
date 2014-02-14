<?php
	/**
	 * Nouvelle façon de paramétrer la génération de documents PDF via Gedooo.
	 *
	 * Ci-dessous, l'ensemble des clés de configuration possibles.
	 *
	 * array(
	 *	'method' => 'classic', // classic, unoconv, cloudooo
	 *	// Ancien serveur Gedooo
	 *	// 'wsdl' => 'http://gedooo.test.adullact.org:8080/axis2/services/OfficeService?wsdl',
	 *	// Nouveau serveur Gedooo
	 *	'wsdl' => 'http://gedooo.test.adullact.org:8880/ODFgedooo/OfficeService?wsdl',
	 *	// Pour Unoconv
	 *	'unoconv_bin' => '/home/cbuffin/Bureau/1/usr/bin/unoconv',
	 *	// Pour Cloudooo
	 *	'cloudooo_host' => '192.168.2.35',
	 *	'cloudooo_port' => '8011'
	 * )
	 */

	// Exemple de configuration avec l'ancien Gedooo
	/*Configure::write(
		'Gedooo',
		array(
			'method' => 'classic',
			'wsdl' => 'http://gedooo.test.adullact.org:8080/axis2/services/OfficeService?wsdl',
		)
	);*/

	// Exemple de configuration avec le nouveau Gedooo et unoconv
	/*Configure::write(
		'Gedooo',
		array(
			'method' => 'unoconv',
			'wsdl' => 'http://gedooo.test.adullact.org:8880/ODFgedooo/OfficeService?wsdl',
			'unoconv_bin' => '/home/cbuffin/Bureau/1/usr/bin/unoconv',
		)
	);*/

	// Exemple de configuration avec le nouveau Gedooo et cloudooo
	Configure::write(
		'Gedooo',
		array(
			'method' => 'cloudooo',
			'wsdl' => 'http://gedooo.test.adullact.org:8880/ODFgedooo/OfficeService?wsdl',
			'cloudooo_host' => 'cloudooo.test.adullact.org',
			'cloudooo_port' => '8011'
		)
	);

	/**
	 * Le chemin complet vers la base de l'application (utilisé par certains shells)
	 *
	 * @default: null
	 */
	Configure::write( 'FULL_BASE_URL', 'http://localhost/webrsa/trunk' );

	Configure::write( 'Cg.departement', 93 );
	Configure::write( 'Utilisateurs.multilogin', false );
	Configure::write( 'with_parentid', false ); // true = cg66
	Configure::write( 'nom_form_ci_cg', 'cg93' );
	Configure::write( 'CG.cantons', false ); // false = cg93, true = cg66
	Configure::write( 'Zonesegeographiques.CodesInsee', true );
	//Configure::write( 'Etatliquidatif.limit', 1000 ); // INFO: commenter pour ne pas avoir de limite

	/**
	 * Champs spécifique selon le CG pour le formulaire de l'APRE
	 * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
	 */
	Configure::write( 'nom_form_apre_cg', 'cg93' );

	// Limit pour le nombre de documents à éditer dans la cohorte par orientation
	Configure::write( 'nb_limit_print', 2000 );


	/**
	 * Permet à l'administrateur d'accéder à toutes les parties de l'application
	 * normalement bloquées aux seules parties de paramétrage renseignées.
	 * Voir AppController::_isAdminAction().
	 * @default false
	 */
	Configure::write( 'Admin.unlockall', true );

	/**
	 * Montant maximal des apres complémentaires pour une personne au cours
	 * de la période de temps définie par Apre.periodeMontantMaxComplementaires.
	 * @default 2600
	 * cg66 -> 3000
	 */
	Configure::write( 'Apre.montantMaxComplementaires', 2600 );

	/**
	 * Période (en nombre d'année) utilisée pour la calcul du montant maximal
	 * des apres complémentaires pour une personne.
	 * @default 1 (une année du 01/01 au 31/12)
	 */
	Configure::write( 'Apre.periodeMontantMaxComplementaires', 2 );

	/**
	 * Paramètre à renseigner pour l'utilisation du bon MVC
	 * @default: vide (pour le CG93), sinon '66' pour le CG66
	 */
	Configure::write( 'Apre.suffixe', null );

	/**
	 * Paramètres à renseigner pour les APRE's forfaitaires
	 * FIXME: doc
	 */
	Configure::write( 'Apre.forfaitaire.montantbase', 250 );
	Configure::write( 'Apre.forfaitaire.montantenfant12', 60 );
	Configure::write( 'Apre.forfaitaire.nbenfant12max', 4 );

	/**
	 * FIXME: vérifier l'existance et les droits
	 * FIXME: accès concurrents ?
	 */
	Configure::write( 'Cohorte.dossierTmpPdfs', APP.'tmp/files/pdf' );

	/**
	 *   Paramètres à renseigner pour les montants des
	 *   forfaits de déplacements des APREs pour le CG66
	 *   @default:   0.20€ pour les forfaits au Km
	 *               23€ pour les frais d'hebergement
	 *               3.81€ pour les frais de repas
	 */
	Configure::write( 'Fraisdeplacement66.forfaitvehicule', 0.20 );
	Configure::write( 'Fraisdeplacement66.forfaithebergt', 23 );
	Configure::write( 'Fraisdeplacement66.forfaitrepas', 3.81 );

	/**
	 * Permet à l'administrateur d'ajouter une adresse pour l'utilisateur
	 * connecté à l'application.
	 * Besoin pour le CG66 lors de l'impression des courriers de rendez-vous
	 * @default false
	 */
	Configure::write( 'User.adresse', false ); // true = cg66, false = cg93


	/**
	 * Paramètre à renseigner pour l'affichage de la bonne fiche de candidature
	 * @default: 'cg93' (pour le CG93), sinon 'cg66' pour le CG66
	 */
	Configure::write( 'ActioncandidatPersonne.suffixe', 'cg93' );

	/**
	 *   Paramètre à renseigner dans le cas d'un affichage plus large du menu du dossier
	 *   @default: false
	 */
	Configure::write( 'UI.menu.large', true );


	/**
	 *   Paramètre à renseigner pour le CG58 pour le lien pointant sur leur application
	 */
	Configure::write( 'UI.menu.lienDemandeur', false );


	/**
	 *   Paramètre à renseigner pour déterminer la plage horaire que l'on dispose pour pouvoir accéder
	 *   aux différents boutons possédant ce paramètre.
	 *   On met 48, pour 48H car la plage de date va de minuit à minuit et donc un formulaire saisi
	 *   un jour à 18h ne serait plus modifiable/validable/supprimable le lendemain.
	 *   @default: 48 --> nombre d'heures pendant lesquelles on pourra accéder au bouton
	 */
	Configure::write( 'Periode.modifiable.nbheure', 48 );



	/**
	 * Champs spécifique selon le CG pour le formulaire des PDOs
	 * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
	 */
	Configure::write( 'nom_form_pdo_cg', 'cg93' );

	/**
	 * Champs spécifique selon le CG pour le formulaire du bilan de parcours / Fiche de saisine
	 * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
	 */
	Configure::write( 'nom_form_bilan_cg', 'cg93' );

	/**
	 * Champs spécifique selon le CG pour le formulaire du Contrat Unqiue d'Insertion
	 * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
	 */
	Configure::write( 'nom_form_cui_cg', 'cg93' );

	/**
	 *   Paramètre pour connaître le pourcentage du 1er versement, lors d'un versement en 2 fois,
	 *   pour les apres présentes dans un état liquidatif
	 *   @default: 60 ---> avant 40 %
	 */
	Configure::write( 'Apre.pourcentage.montantversement', 60 );

	/**
	 * Permet de désactiver l'utilisation des jetons sur les dossiers
	 * Si à false, on utilise les jetons sur les dossiers.
	 * Si à true, on n'utilise pas les jetons sur les dossiers.
	 * @default false
	 */
	Configure::write( 'Jetons2.disabled', false );

	/**
	 *   Paramètre pour définir les taux dans le formulaire du CUI :
	 *   Cui.taux.fixe                   => Taux fixé par l'arrêté du Préfet de région (en % )
	 *   Cui.taux.prisencharge           => Taux de prise en charge effectif si le Conseil Général fixe
	 *                                       un taux supérieur au taux fixé par le Préfet de région (en %)
	 *   Cui.taux.financementexclusif    => Financement exclusif du Conseil Général, Si oui, taux (en %)
	 *   @default: à définir par chaque CG
	 */
	Configure::write( 'Cui.taux.fixe', 60 );
	Configure::write( 'Cui.taux.prisencharge', 70 );
	Configure::write( 'Cui.taux.financementexclusif', 60 );

	/**
	 * Permet la pagination progressive, cad. qu'on ne compte pas le nombre
	 * d'enregistrements totaux, mais que l'on regarde seulement si la
	 * page suivante existe.
	 *
	 * Ce paramètre concerne toutes les paginations.
	 *
	 * @default: false (pagination normale)
	 */
	Configure::write( 'Optimisations.progressivePaginate', true );

	/**
	 * Surcharge de la pagination progressive pour les cohorte d'orientations, demandes orientées
	 */
	Configure::write( 'Optimisations.Cohortes_orientees.progressivePaginate', false );

	/**
	 * Surcharge de la pagination progressive pour l'action nouveaux
	 * du contrôleur Cohortesci.
	 */
	Configure::write( 'Optimisations.Cohortesci_nouveaux.progressivePaginate', false );

	/**
	 * Surcharge de la pagination progressive pour l'action avisdemande
	 * du contrôleur Cohortespdos.
	 */
	Configure::write( 'Optimisations.Cohortespdos_avisdemande.progressivePaginate', false );

	/**
	 * Surcharge de la pagination progressive pour l'action cohorte
	 * du contrôleur Relancesnonrespectssanctionseps93.
	 */
	Configure::write( 'Optimisations.Relancesnonrespectssanctionseps93_cohorte.progressivePaginate', false );

	/**
	 * Variables apparaissant dans la fiche de calcul du journal de traitement d'une PDO
	 */
	Configure::write( 'Traitementpdo.fichecalcul_coefannee1', 34 );
	Configure::write( 'Traitementpdo.fichecalcul_coefannee2', 25 );

	Configure::write( 'Traitementpdo.fichecalcul_cavntmax', 80300 );
	Configure::write( 'Traitementpdo.fichecalcul_casrvmax', 32100 );

	Configure::write( 'Traitementpdo.fichecalcul_abattbicvnt', 71 );
	Configure::write( 'Traitementpdo.fichecalcul_abattbicsrv', 50 );
	Configure::write( 'Traitementpdo.fichecalcul_abattbncsrv', 34 );

	/**
	 * Affiche ou non l'alerte de fin de session et exécute la redirection
	 */
	Configure::write( 'alerteFinSession', false );

	/**
	 * Nombre de jours entre la date de prise de décision concernant un dossier d'EP
	 * pour le thème "non respect et sanctions du CG 93" et un nouveau passage en
	 * EP pour ce même thème.
	 */
	Configure::write( 'Nonrespectsanctionep93.relanceDecisionNonRespectSanctions', 60 );

	/**
	 * Nombre de jours entre la date d'édition de la notification d'orientation et
	 * l'envoi de la première relance pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.relanceOrientstructCer1', 60 );

	/**
	 * Nombre de jours entre la date de première relance et
	 * l'envoi de la seconde relance pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.relanceOrientstructCer2', 30 );

	/**
	 * Nombre de jours entre la date de seconde relance et
	 * l'envoi de la troisième relance pour le thème "non respect et sanctions du CG 93"
	 */
// 	Configure::write( 'Nonrespectsanctionep93.relanceOrientstructCer3', 30 );

	/**
	 * Nombre de jours entre la date de fin du contrat précédent et l'envoi de
	 * la première relance pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.relanceCerCer1', 60 );

	/**
	 * Nombre de jours entre la date de première relance et de
	 * la seconde relance pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.relanceCerCer2', 30 );

	/**
	 * Montant de la réduction d'allocation lors d'un premier passage en EP pour
	 * le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.montantReduction', 100 );

	/**
	 * Durée du délai supplémentaire (en jours) lors d'un premier passage en EP
	 * pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.dureeSursis', 31 );

	/**
	 * Durée du délai (intervalle) accordé pour pour la détection des DO 19
	 * pour le thème "non respect et sanctions du CG 93" grâce au shell
	 * automatisationseps
	 *
	 * Voir le document app/docs/Documentation administrateurs.odt, partie
	 * "Intervalles PostgreSQL"
	 */
	Configure::write( 'Nonrespectsanctionep93.intervalleCerDo19', '1 month' );

	/**
	 * Durée du délai (en jours) accordé pour la création d'un nouveau contrat pour
	 * le thème "détection brsa en parcours social sans réorientation"
	 */
	Configure::write( 'Nonorientationproep93.delaiCreationContrat', 60 );

	/**
	 * Durée du délai (en jours) accordé pour la régularisation de l'état à Pôle Emploi
	 * pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.delaiRegularisation', 60 );

	/**
	 * Durée du délai (en jours) accordé après un passage en EP dont la décision a été
	 * d'accorder un délai supplémentaire pour le thème "non respect et sanctions du CG 93"
	 */
	Configure::write( 'Nonrespectsanctionep93.decisionep.delai', 40 );

	/**
	 * Montant de la réduction d'allocation lors d'un premier passage en EP pour
	 * le thème "non respect des termes du CER du CG 93"
	 */
	Configure::write( 'Signalementep93.montantReduction', 100 );

	/**
	 * Durée du sursis (en jours) lors d'un premier passage en EP pour
	 * le thème "non respect des termes du CER du CG 93"
	 */
	Configure::write( 'Signalementep93.dureeSursis', 31 );

	/**
	 * Durée de la tolérance (en nombre de jours) par-rapport à la date de fin
	 * d'un CER que l'on voudrait passer en EP pour le thème "non respect des
	 * termes du CER du CG 93"
	 */
	Configure::write( 'Signalementep93.dureeTolerance', 365 );

	/**
	 * Durée du délai (en jours) accordé après un passage en EP dont la décision a été
	 * d'accorder un délai supplémentaire pour le thème "Non respect du CER"
	 */
	Configure::write( 'Signalementep93.decisionep.delai', 40 );

	/**
	 * Nombre de jours entre deux passages successifs en EP pour une même personne
	 * et une même thématique.
	 * FIXME: à rendre plus générique (pour toutes les thématiques)
	 */
	Configure::write( 'Dossierep.nbJoursEntreDeuxPassages', 31 );

	/**
	 * Configuration pour le dialogue avec un CMS (système de gestion de contenu)
	 * comme Alfresco, servant à stocker les PDF
	 */
	Configure::write(
			'Cmis', array(
		'url' => 'http://xxxxxx/alfresco/service/api/cmis',
		'username' => 'user',
		'password' => 'pass',
		'prefix' => '/Sites/Webrsa',
			)
	);

	/**
	 * Permet de rajouter des conditions aux conditions de recherches suivant
	 * le paramétrage des service référent dont dépend l'utilisateur connecté.
	 *
	 * @default false
	 */
	Configure::write( 'Recherche.qdFilters.Serviceinstructeur', ( Configure::read( 'Cg.departement' ) == 58 ) );


	/**
	 *  Permet de définir si on peut ou non ajouter une nouvelle orientation à un dossier
	 *   - toppersdrodevorsa     --> par défaut '1' (personne soumise à droit et devoir)
	 *   - situationetatdosrsa   --> par défaut array( 'Z', '2', '3', '4') (dossier dans un droit ouvert et versable)
	 *
	 * INFO: depuis le passage en CakePHP 2.x, il faut mettre les valeurs numériques entre apostrophes.
	 */
	Configure::write( 'AjoutOrientationPossible.toppersdrodevorsa', '1' ); // aucune distincton sur le soumis à droit et devoir array( 0,1 )
	Configure::write( 'AjoutOrientationPossible.situationetatdosrsa', array( 'Z', '2', '3', '4' ) );// on prend toutes les valeurs possibles de l'état du dossier RSA

	/**
	 * Permet de spécifier si les recherches sur l'identifiant Pôle Emploi d'un
	 * allocataire doivent se faire sur les 8 derniers chiffres de l'identifiant
	 * (true) ou sur la totalité de celui-ci (false).
	 *
	 * @default false
	 */
	Configure::write( 'Recherche.identifiantpecourt', false );


	/**
	 * Durée du délai (intervalle) entre la date de validation de l'orientation et la date
	 * d'inscription au Pôle Emploi
	 *
	 * Voir le document app/docs/Documentation administrateurs.odt, partie
	 * "Intervalles PostgreSQL"
	 */
	Configure::write( 'Selectionnoninscritspe.intervalleDetection', '2 months' );

	/**
	 * Gestion des anomalies
	 */
	Configure::write( 'Optimisations.Gestionsanomaliesbdds.progressivePaginate', false );
	Configure::write( 'Situationdossierrsa.etatdosrsa.ouvert', array( 'Z', '2', '3', '4' ) );


	/**
	 * 	Délai pour la détection des CERs arrivant à échéance
	 */
	Configure::write( 'Criterecer.delaiavanteecheance', '1 month' );


	/**
	 * 	Pour le CG66: Délai durant lequel les dossiers d'EP ne seront ni détectables, ni sélectionnables dans la corbeille
	 * 	des dossiers devant passer en EP
	 * 	@default: null
	 * 	@CG66: 1 month 15 days
	 * 	Voir le document app/docs/Documentation administrateurs.odt, partie
	 * 	"Intervalles PostgreSQL"
	 */
	Configure::write( 'Dossierep.delaiavantselection', null );


	/**
	 * 	Valeurs prises par le champ natpf pour déterminer si le dossier est en RSA Socle
	 */
	Configure::write( 'Detailcalculdroitrsa.natpf.socle', array( 'RSD', 'RSI', 'RSU', 'RSJ' ) );

	/**
	 * Permet d'indiquer les valeurs par défaut des formulaires de recherche.
	 * Pour l'instant, ne fonctionne qu'avec le contrôleur Cohortes: "Cohortes" -> "Orientation"
	 * 	- Demandes non orientées
	 * 	- Demandes en attente de validation d'orientation
	 * 	- Demandes orientées
	 * ATTENTION:
	 * 	1°) le filtre Filtre.paginationNombreTotal:
	 * 		a°) Est la valeur inverse de progressivePaginate (cf. supra; car avec la pagination progressive, pas de nombre total)
	 * 		b°) SURCHARGE la valeur de (dans l'ordre):
	 * 			- Optimisations.progressivePaginate
	 * 			- Optimisations.Cohortes.progressivePaginate
	 * 			- Optimisations.Cohortes_orientees.progressivePaginate
	 * 	2°) Le nombre de clés (chaînes de caractères se trouvant entre points) ne doit pas excéder 3. Exemple:
	 * 		- Configure::write( 'Filtresdefaut.Cohortes_orientees.Dossier', array( 'dernier' => false ) ); est pris en compte
	 * 		- Configure::write( 'Filtresdefaut.Cohortes_orientees.Dossier.dernier', false ); n'est pas pris en compte
	 */
	Configure::write(
			'Filtresdefaut.Cohortes_nouvelles', array(
		'Detailcalculdroitrsa' => array(
			'natpf_choice' => true, // Case à cocher "Filtrer par nature de prestation (RSA Socle)"
			'natpf' => array( 'RSD', 'RSI' ) // Cases à cocher "Nature de la prestation"
		),
		'Filtre' => array(
			'oridemrsa' => array( 'DEM' ), // Cases à cocher "Code origine demande Rsa"
			'paginationNombreTotal' => false // Case à cocher "Obtenir le nombre total de résultats (plus lent)"
		),
		'Dossier' => array( 'dernier' => true ), // Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
		'Situationdossierrsa' => array(
			'etatdosrsa_choice' => true, // Case à cocher "Filtrer par état du dossier"
			'etatdosrsa' => array( 2, 3, 4 ) // Cases à cocher "État du dossier RSA"
		),
			)
	);

	/**
	 * Copie des valeurs par défaut des filtres des demandes à orienter pour les demandes en attente.
	 */
	Configure::write( 'Filtresdefaut.Cohortes_enattente', Configure::read( 'Filtresdefaut.Cohortes_nouvelles' ) );

	/**
	 * Copie des valeurs par défaut des filtres des demandes à orienter pour les demandes orientées,
	 * surcharge de la case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
	 * pour des raisons de performances
	 */
	Configure::write( 'Filtresdefaut.Cohortes_orientees', Configure::read( 'Filtresdefaut.Cohortes_nouvelles' ) );
	Configure::write( 'Filtresdefaut.Cohortes_orientees.Dossier', array( 'dernier' => false ) );

	/**
	 * Harry - CG93
	 * Apre complementaire CG93
	 * @default true
	 */
	Configure::write( 'Apre.complementaire.query', true );

	/**
	 * Url pointant sur le serveur qui nous intéresse pour lcontacter QUERY
	 * %s récupère le nom de l'utilisateur connecté
	 */
	Configure::write( 'Apre.complementaire.queryUrl', 'http://rsa-psql-p02.cg93.fr/query/?nomuser=%s' );

	/**
	 * 	Mise en paramétrage de la liste des chargés d'insertion et secrétaire liés à une fiche de candidature
	 * 	@default: id des group auxquels les personnes sont liées
	 * 	Mise en place suite à la demande d'améliorations du 28/02/2012 ( #5630 )
	 */
	Configure::write( 'Chargeinsertion.Secretaire.group_id', array( 16, 7, 12 ) );

	/**
	 * Pagination progressive pour la cohorte d'affectation de référents.
	 */
	Configure::write( 'Optimisations.Cohortesreferents93_affecter.progressivePaginate', true );

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortesreferents93/affecter
	 * (menu "CER" > "1. Affectation d'un référent" > "Référents à affecter") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortesreferents93_affecter',
		array(
			'Search' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'Dossier' => array( 'dernier' => true ),
				'Orientstruct' => array(
					// Case à cocher "Filtrer par date d'orientation"
					'date_valid' => false,
					// Du (inclus)
					'date_valid_from' => date( 'Y-m-d', strtotime( '-1 month' ) ),
					// Au (inclus)
					'date_valid_to' => date( 'Y-m-d', strtotime( '+1 day' ) ),
				),
				'Situationdossierrsa' => array(
					// Case à cocher "Filtrer par état du dossier"
					'etatdosrsa_choice' => true,
					// Cases à cocher "État du dossier RSA"
					'etatdosrsa' => array( 2, 3, 4 )
				),
			),
		)
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortesreferents93/affectes
	 * (menu "CER" > "1. Affectation d'un référent" > "Référents déjà affectés") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortesreferents93_affectes',
		Set::merge(
			Configure::read( 'Filtresdefaut.Cohortesreferents93_affecter' ),
			array(
				'Search' => array(
					'PersonneReferent' => array(
						'dddesignation' => false,
						// Du (inclus)
						'dddesignation_from' => date( 'Y-m-d', strtotime( '-1 month' ) ),
						// Au (inclus)
						'dddesignation_to' => date( 'Y-m-d', strtotime( '+1 day' ) ),
					),
					'Pagination' => array(
						'nombre_total' => true
					)
				)
			)
		)
	);

	/**
	 * Permet de rechercher le dernier dossier d'un allocataire dans la table
	 * derniersdossiersallocataires (il faudra mettre le shell Derniersdossiersallocataires
	 * en tâche planifiée) afin de se passer d'une sous-requête coûteuse dans les
	 * recherches.
	 *
	 * @param boolean
	 * @default null
	 */
	Configure::write( 'Optimisations.useTableDernierdossierallocataire', true );

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/visualisation
	 * (menu "CER" > "5. Tableau de suivi") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_visualisation',
		array(
			'Search' => array(
				'Contratinsertion' => array(
					'dernier' => true
				),
				'Dossier' => array(
					'dernier' => true
				)
			)
		)
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/saisie
	 * (menu "CER" > "2. Saisie CER") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_saisie',
		Configure::read( 'Filtresdefaut.Cohortescers93_visualisation' )
	);


	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/avalidercpdv
	 * (menu "CER" > "3. Validation Responsable") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_avalidercpdv',
		Configure::read( 'Filtresdefaut.Cohortescers93_visualisation' )
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/premierelecture
	 * (menu "CER" > "4. Décison CG -> 4.1. Première lecture") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_premierelecture',
		Configure::read( 'Filtresdefaut.Cohortescers93_visualisation' )
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/validationcadre
	 * (menu "CER" "4. Décison CG -> 4.2. Validation cadre") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_validationcadre',
		Configure::read( 'Filtresdefaut.Cohortescers93_visualisation' )
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortescers93/validationcs
	 * (menu "CER" "4. Décison CG -> 4.1. Validation CS") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortescers93_validationcs',
		Configure::read( 'Filtresdefaut.Cohortescers93_visualisation' )
	);


	/**
	 * Lorsque apache est utilisé en mode CGI, les fonctions apache_get_version()
	 * et apache_get_modules() ne sont pas disponibles. Du coup, on passe par
	 * la fonction exec() pour interroger directement le binaire apache.
	 *
	 * Ce paramètre de configuration permet de spécifier le chemin complet vers
	 * le binaire apache.
	 *
	 * @default /usr/sbin/apache2
	 */
	Configure::write( 'apache_bin', '/usr/sbin/apache2' );

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortetransfertspdvs93/atransferer
	 * (menu "CER" > "5. Tableau de suivi") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortestransfertspdvs93_atransferer',
		array(
			'Search' => array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => '1'
				),
				'Dossier' => array(
					'dernier' => '1',
				),
				'Orientstruct' => array(
					'typeorient_id' => 1
				),
				'Situationdossierrsa' => array(
					'etatdosrsa_choice' => '1',
					'etatdosrsa' => array( '2', '3', '4' )
				)
			)
		)
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de recherche de /cohortetransfertspdvs93/transferes
	 * (menu "CER" > "5. Tableau de suivi") du CG 93.
	 */
	Configure::write(
		'Filtresdefaut.Cohortestransfertspdvs93_transferes',
		array(
			'Search' => array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => '1'
				),
				'Dossier' => array(
					'dernier' => '1',
				),
				'Orientstruct' => array(
					'typeorient_id' => 1
				),
				'Situationdossierrsa' => array(
					'etatdosrsa_choice' => '1',
					'etatdosrsa' => array( '2', '3', '4' )
				)
			)
		)
	);


	/**
	 * Permet de donner des valeurs par défaut au formulaire de cohorte des PDOs (cohortespdos/avisdemande)
	 */
	Configure::write(
		'Filtresdefaut.Cohortespdos_avisdemande',
		array(
			'Search' => array(
				'Dossier' => array(
					'dernier' => '1',
				)
			)
		)
	);

	/**
	 * Permet de donner des valeurs par défaut au formulaire de cohorte des PDOs (cohortespdos/valide)
	 */
	Configure::write(
		'Filtresdefaut.Cohortespdos_valide',
		Configure::read( 'Filtresdefaut.Cohortespdos_avisdemande' )
	);


	/**
	 * Variable contenant un array avec les id des typesorients du social, sociopro et de l'emploi
	 */
	Configure::write( 'Orientstruct.typeorientprincipale', array( 'Socioprofessionnelle' => array( 1 ), 'Social' => array( 2 ), 'Emploi' => array( 3 ) ) );

	/**
	 * Précise la durée de détection des CER qui passent dans l'état "En attente
	 * de renouvellement" qui sera utilisée dans la partie "Workflow CER", à
	 * l'étape "2. Saisie d'un CER" pour le CG 93.
	 *
	 * @value string
	 * @default null
	 */
	Configure::write( 'Cohortescers93.saisie.periodeRenouvellement', '1 month' );

	/**
	 * Permet de débloquer la fonctionnalité "Enregistrement automatique d'un RDV"
	 * lors de la saisie d'un CER.
	 *
	 * @var boolean
	 * @default null (false)
	 */
	Configure::write( 'Contratinsertion.RdvAuto.active', true );

	/**
	 * L'id technique de l'enregistrement de la table typesrdv ("Objet du RDV")
	 * qui concerne la rédaction d'un CER (ex.: "Individuel").
	 *
	 * Utilisé pour créer automatiquement un RDV lors de l'enregistrement d'un CER.
	 *
	 * @var integer
	 * @default null
	 */
	Configure::write( 'Contratinsertion.RdvAuto.typerdv_id', 16 );

	/**
	 * L'id technique de l'enregistrement de la table statutsrdvs ("Statut du RDV")
	 * qui indique que l'allocataire était présent (ex.: "Présent").
	 *
	 * Utilisé pour créer automatiquement un RDV lors de l'enregistrement d'un CER.
	 *
	 * @var integer
	 * @default null
	 */
	Configure::write( 'Contratinsertion.RdvAuto.statutrdv_id', 1 );

	/**
	 * L'id technique de l'enregistrement de la table thematiquesrdvs ("Thématique du RDV")
	 * qui concerne la rédaction d'un CER (ex.: "Pour un Contrat d'Engagement Réciproque").
	 *
	 * Utilisé pour créer automatiquement un RDV lors de l'enregistrement d'un CER.
	 *
	 * @var integer
	 * @default null
	 */
	Configure::write( 'Contratinsertion.RdvAuto.thematiquerdv_id', 5 );

	/**
	 * Permet-on à l'utilisateur de se reconnecter alors que sa session n'est pas
	 * clôturée ni expirée ?
	 *
	 * @var boolean
	 * @default null (false)
	 */
	Configure::write( 'Utilisateurs.reconnection', true );

	/**
	 * Configuration de la gestion des mots de passe aléatoires.
	 *
	 * Configuration par-défaut:
	 * <pre>
	 * array(
	 *	// Permet-on l'utilisation de la fonctionnalité "Mot de passe oublié" sur la page de login ?
	 * 	'mail_forgotten' => false,
	 *	// Quelles sont les possibilités lors de la création d'un mot de passe aléatoire ?
	 * 	'possible' => array(
	 *		// Nombres -> de 0 à 9
	 * 		'number' => true,
	 *		// Minuscules -> de a à z
	 * 		'lower' => true,
	 *		// Majuscules -> de a à z
	 * 		'upper' => true,
	 *		// Caractères spéciaux -> ,;.!?*+-
	 * 		'symbol' => true,
	 *		// Supprime-t'on les caractères équivoques (1, i, l, I et 0, o, O) ?
	 * 		'typesafe' => true,
	 * 	),
	 *	// Quels sont les caractères obligatoires ?
	 * 	'required' => array(
	 *		// Au moins un nombre ?
	 * 		'number' => true,
	 *		// Au moins une lettre en minuscule ?
	 * 		'lower' => false,
	 *		// Au moins une lettre en majuscule ?
	 * 		'upper' => false,
	 *		// Au moins un caractère spécial ?
	 * 		'symbol' => true,
	 * 	),
	 * )
	 * </pre>
	 */
	Configure::write(
		'Password',
		array(
			'mail_forgotten' => false,
			'possible' => array(
				'number' => true,
				'lower' => true,
				'upper' => true,
				'symbol' => true,
				'typesafe' => true,
			),
			'required' => array(
				'number' => true,
				'lower' => false,
				'upper' => false,
				'symbol' => true,
			),
		)
	);

    /**
	 * Variable contenant une chaîne de caractères (stockée en base) pour le
	 * n° de convention annuelle d'objectifs et de moyens
     * (unqiue par année et qui devant être changé chaque année)
     * Cui.numconventionobj
	 */
    Configure::write( 'Cui.Numeroconvention', '066130000100' );

	/**
	 * Conditions de base pour le brouillon des statistiques ministérielles.
	 * De base (en dur), on s'assure que l'allocataire soit soumis à droits et
	 * devoirs. Par contre, on ne s'assure pas de l'état du dossier, hors les
	 * allocataires soumis à droits et devoirs devraient normalement n'être que
	 * dans des dossiers en états ouverts.
	 *
	 * Exemple:
	 * <pre>
	 * Configure::write(
	 * 	'Statistiqueministerielle.conditions_base',
	 * 	array(
	 *		'Situationdossierrsa.etatdosrsa' => array( '2', '3', '4' )
	 * 	)
	 * );
	 * </pre>
	 *
	 * @var array
	 */
    Configure::write(
		'Statistiqueministerielle.conditions_base',
		array(
			'Situationdossierrsa.etatdosrsa' => array( '2', '3', '4' )
		)
	);

	/**
	 * Conditions pour la partie "Indicateurs d'organismes" des statistiques
	 * ministérielles.
	 *
	 * Les modèles disponibles dans les conditions sont:
	 *   - Structurereferente: structure référente de la dernière orientation
	 *   - Typeorient: type d'orientation de la structure référente de la dernière orientation
	 *   - Referent: référent du parcours
	 *   - Structurereferentereferent: structure référente du référent du parcours
	 *   - Typeorientreferent: type d'orientation de la structure référente du référent du parcours
	 *
	 * Lorsque aucune conditions n'est définie pour une catégorie d'organismes, le
	 * résultat sera 'N/A'.
	 *
	 * Exemple:
	 * <pre>
	 * Configure::write(
	 * 	'Statistiqueministerielle.conditions_indicateurs_organismes',
	 * 	array(
	 * 		// Pôle Emploi (PE)
	 * 		'pole_emploi' => array(
	 * 			'Structurereferentereferent.id' => 2
	 * 		),
	 * 		'oppp_autre_pole_emploi' => null,
	 * 		'entreprise_travail_temporaire' => null,
	 * 		'organisme_creation_developpement_entreprise' => null,
	 * 		'iae' => null,
	 * 		'autre_professionnel' => null,
	 * 		'service_departement' => null,
	 * 		'service_departement_professionnel' => null,
	 * 		'service_departement_social' => null,
	 * 		'caf_msa' => null,
	 * 		'ccas_cias' => null,
	 * 		'autres' => null,
	 * 	)
	 * );
	 * </pre>
	 *
	 * @var array
	 */
	Configure::write(
		'Statistiqueministerielle.conditions_indicateurs_organismes',
		array(
			// Pôle Emploi (PE)
			'pole_emploi' => array(),
			// Organisme public de placement professionnel autre que PE (maison de l'emploi, PLIE, mission locale, ...)
			'oppp_autre_pole_emploi' => array(),
			// Entreprise de travail temporaire, agence privée de placement
			'entreprise_travail_temporaire' => array(),
			// Organisme d'appui à la création et au développement d'entreprise
			'organisme_creation_developpement_entreprise' => array(),
			// Insertion par l'activité économique (IAE) (uniquement si le référent appartient à l'IAE)
			'iae' => array(),
			// Autre organisme de placement professionnel
			'autre_professionnel' => array(),
			// Service du département
			'service_departement' => array(),
			// Service du département, dont orientation à dominante professionnelle
			'service_departement_professionnel' => array(),
			// Service du département, dont orientation à dominante sociale
			'service_departement_social' => array(),
			// CAF, MSA
			'caf_msa' => array(),
			// CCAS, CIAS
			'ccas_cias' => array(),
			// Autres organismes d'insertion
			'autres' => array(),
		)
	);

	/**
	 * Conditions pour la partie "Indicateurs de délais" des statistiques ministérielles.
	 *
	 * Les modèles disponibles dans les conditions sont:
	 *   - Contratinsertion: premier CER de l'allocataire
	 *   - Structurereferenterecer: structure référente du premier CER de l'allocataire
	 *   - Typeorientcer: type d'orientation de la structure référente du premier CER de l'allocataire
	 *
	 * Lorsque aucune conditions n'est définie pour une catégorie de délais, le
	 * résultat sera 'N/A'.
	 *
	 * Exemple:
	 * <pre>
	 * Configure::write(
	 * 	'Statistiqueministerielle.conditions_types_cers',
	 * 	array(
	 * 		// Permet d'indiquer que la structure gère les PPPAE
	 * 		'ppae' => array(
	 * 			'Typeorientcer.id' => 2
	 * 		)
	 * 	)
	 * );
	 * </pre>
	 *
	 * @var array
	 */
	Configure::write(
		'Statistiqueministerielle.conditions_types_cers',
		array(
			'ppae' => array()
		)
	);

	/**
	 * Conditions pour la partie "Indicateurs de caractéristiques des contrats" des statistiques ministérielles.
	 *
	 * Les modèles disponibles dans les conditions sont:
	 *   - Contratinsertion: premier CER de l'allocataire
	 *   - Structurereferenterecer: structure référente du premier CER de l'allocataire
	 *   - Typeorientcer: type d'orientation de la structure référente du premier CER de l'allocataire
	 *
	 * Lorsque aucune conditions n'est définie pour une catégorie de contrats, le
	 * résultat sera 'N/A'.
	 *
	 * Exemple:
	 * <pre>
	 * Configure::write(
	 * 	'Statistiqueministerielle.conditions_caracteristiques_contrats',
	 * 	array(
	 *		// Conditions permettant de cibler des contrat d'insertion RMI
	 * 		'contrat_rmi' => null,
	 *		// Conditions permettant de cibler des contrat RSA expérimentaux
	 * 		'cer_experimental' => null,
	 *		// Conditions permettant de cibler des PPAE
	 * 		'ppae' => null,
	 * 	)
	 * );
	 * </pre>
	 *
	 * @var array
	 */
	Configure::write(
		'Statistiqueministerielle.conditions_caracteristiques_contrats',
		array(
			'contrat_rmi' => null,
			'cer_experimental' => null,
			'ppae' => null,
		)
	);

	/**
	 * Conditions pour la partie "Indicateurs de motifs de réorientations" des statistiques ministérielles.
	 *
	 * Les modèles disponibles dans les conditions sont:
	 *   - Orientstruct: dernière orientation de l'allocataire
	 *   - Typeorient: type d'orientation de la structure référente de la dernière orientation de l'allocataire
	 *   - Orientstructpcd: orientation précédente de l'allocataire
	 *   - Typeorientpcd: type d'orientation de la structure référente de l'orientation précédente de l'allocataire
	 *
	 * Lorsque aucune conditions n'est définie pour un type de réorientation, le
	 * résultat sera 'N/A'.
	 *
	 * Exemple:
	 * <pre>
	 * Configure::write(
	 * 	'Statistiqueministerielle.conditions_indicateurs_motifs_reorientation',
	 * 	array(
	 *		// Conditions permettant de cibler des réorientations suite à une orientation initiale inadaptée
	 * 		'orientation_initiale_inadaptee' => null,
	 *		// Conditions permettant de cibler des réorientations suite à un changement de situation de la personne
	 * 		'changement_situation_allocataire' => null,
	 * 	)
	 * );
	 * </pre>
	 *
	 * @var array
	 */
	Configure::write(
		'Statistiqueministerielle.conditions_indicateurs_motifs_reorientation',
		array(
			'orientation_initiale_inadaptee' => null,
			'changement_situation_allocataire' => null,
		)
	);

	/**
	 * Configuration du module "Tableaux de suivi d'activité PDV".
	 */
	Configure::write(
		'Tableausuivipdv93',
		array(
			// Tous tableaux: l'id du types de rendez-vous "Individuel" qui sera prise en compte
			'typerdv_id' => array( 16 ),
			// Tous tableaux: l'id ou les ids des statuts de rendez-vous ("honoré", "non honoré", "prévu") qui seront pris en compte
			'statutrdv_id' => array( 1 ),
			// Tableaux 1 B 4: les valeurs des numéros du code famille de l'éction d'insertion
			'numcodefamille' => array(
				'acteurs_sociaux' => array( 7 ),
				'acteurs_sante' => array( 8 ),
				'acteurs_culture' => array( 9 )
			),
			//
			// Conditions à prendre en compte pour limiter la liste des structures référentes/PDVs
			// Les modèles disponibles sont: Pdv (alias de Structurereferente) et Typeorient
			'conditionsPdv' => array(
				'OR' => array(
					'Typeorient.id' => Configure::read( 'Orientstruct.typeorientprincipale.Socioprofessionnelle' ),
					'Typeorient.parentid' => Configure::read( 'Orientstruct.typeorientprincipale.Socioprofessionnelle' ),
				),
				'Pdv.lib_struc LIKE' => '%Projet de Ville%'
			),
			// Paramétrages spéciaux pour le tableau 1 B 6
			'Tableau1b6' => array(
				// Rendez-vous collectif
				'typerdv_id' => array( 15 ),
				// Statuts prévus et honorés
				'statutrdv_id_prevu_honore' => array( 1, 2 ),
				// Les codes thèmes des thématiques de RDV (id de thematiquesrdvs => code)
				//	E: Emploi (TRE, découverte des métiers, .recherche demploi par internet,...)
				//	F : Formation (présentations d'actions organisées par des organismes, sensibilisation aux outils informatiques, ...)
				//	VS : Vie Sociale (soutien administratif, logement, famille, mobilité, ...)
				//	LCV : Loisirs, Culture, et Vacances (relais cultures du coeur, séjours vacances, ....)
				//	IRSA : Information dispositif RSA (dispositif RSA uniquement)
				//	2AD : Autres Accès aux Droits (retraite, CMU, transport, ...)
				//	S : Santé (prévention, ...)
				//	3R : Resocialisation, redynamisation, revalorisation (image de soi, ...)
				//	FM : Forum et manifestation : Piloté ou copiloté par le Projet de ville
				'map_thematiques_themes' => array(
					4 => 'E',
					3 => 'FM',
				)
			)
		)
	);
?>