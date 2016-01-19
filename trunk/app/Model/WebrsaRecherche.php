<?php
	/**
	 * Code source de la classe WebrsaRecherche.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'Folder', 'Utility' );

	/**
	 * La classe WebrsaRecherche ...
	 *
	 * @package app.Model
	 */
	class WebrsaRecherche extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRecherche';

		/**
		 * Pas de table liée.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Liste des moteurs de recherche, exports CSV, cohortes, par département,
		 * utilisé dans la vérification de l'application.
		 *
		 * @todo ajout des clés décrivant le ou les départements utilisateurs
		 * @todo: utiliser ces informations dans les contrôleurs ?
		 * @todo Voir l'onglet "Environnement logiciel" > "WebRSA" > "Champs spécifiés dans
		 * le webrsa.inc" de la vérification de l'application.
		 *
		 * @var array
		 */
		public $searches = array(
			'ActionscandidatsPersonnes.search' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaRechercheActioncandidatPersonne',
				'component' => 'WebrsaRecherchesActionscandidatsPersonnesNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'ActionscandidatsPersonnes.exportcsv' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaRechercheActioncandidatPersonne',
				'component' => 'WebrsaRecherchesActionscandidatsPersonnesNew',
				'keys' => array( 'results.fields' )
			),
			'ActionscandidatsPersonnes.cohorte_enattente' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaCohorteActioncandidatPersonneEnattente',
				'component' => 'WebrsaCohortesActionscandidatsPersonnes',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'ActionscandidatsPersonnes.exportcsv_enattente' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaCohorteActioncandidatPersonneEnattente',
				'component' => 'WebrsaCohortesActionscandidatsPersonnes',
				'keys' => array( 'results.fields' )
			),
			'ActionscandidatsPersonnes.cohorte_enattente' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaCohorteActioncandidatPersonneEncours',
				'component' => 'WebrsaCohortesActionscandidatsPersonnes',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'ActionscandidatsPersonnes.exportcsv_enattente' => array(
				'departement' => array( 66 ),
				'modelName' => 'ActioncandidatPersonne',
				'modelRechercheName' => 'WebrsaCohorteActioncandidatPersonneEncours',
				'component' => 'WebrsaCohortesActionscandidatsPersonnes',
				'keys' => array( 'results.fields' )
			),
			'Apres.search' => array(
				'departement' => array( 66, 93 ),
				'modelName' => 'Apre',
				'modelRechercheName' => 'WebrsaRechercheApre',
				'component' => 'WebrsaRecherchesApresNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres.exportcsv' => array(
				'departement' => array( 66, 93 ),
				'modelName' => 'Apre',
				'modelRechercheName' => 'WebrsaRechercheApre',
				'component' => 'WebrsaRecherchesApresNew',
				'keys' => array( 'results.fields' )
			),
			'Apres.search_eligibilite' => array(
				'departement' => array( 93 ),
				'modelName' => 'Apre',
				'modelRechercheName' => 'WebrsaRechercheApreEligibilite',
				'component' => 'WebrsaRecherchesApresEligibiliteNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres.exportcsv_eligibilite' => array(
				'departement' => array( 93 ),
				'modelName' => 'Apre',
				'modelRechercheName' => 'WebrsaRechercheApreEligibilite',
				'component' => 'WebrsaRecherchesApresEligibiliteNew',
				'keys' => array( 'results.fields' )
			),
			'Apres66.cohorte_validation' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Validation',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres66.exportcsv_validation' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Validation',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields' )
			),
			'Apres66.cohorte_imprimer' => array(
				'type' => 'search',
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Imprimer',
				'component' => 'WebrsaCohortesApres66Impressions',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres66.exportcsv_imprimer' => array(
				'type' => 'search',
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Imprimer',
				'component' => 'WebrsaCohortesApres66Impressions',
				'keys' => array( 'results.fields' )
			),
			'Apres66.cohorte_notifiees' => array(
				'type' => 'search',
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Imprimer',
				'component' => 'WebrsaCohortesApres66Impressions',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres66.exportcsv_notifiees' => array(
				'type' => 'search',
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Imprimer',
				'component' => 'WebrsaCohortesApres66Impressions',
				'keys' => array( 'results.fields' )
			),
			'Apres66.cohorte_transfert' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Transfert',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres66.exportcsv_transfert' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Transfert',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields' )
			),
			'Apres66.cohorte_traitement' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Traitement',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Apres66.exportcsv_traitement' => array(
				'departement' => array( 66 ),
				'modelName' => 'Apre66',
				'modelRechercheName' => 'WebrsaCohorteApre66Traitement',
				'component' => 'WebrsaCohortesApres66New',
				'keys' => array( 'results.fields' )
			),
			'Bilansparcours66.search' => array(
				'departement' => array( 66 ),
				'modelName' => 'Bilanparcours66',
				'modelRechercheName' => 'WebrsaRechercheBilanparcours66',
				'component' => 'WebrsaRecherchesBilansparcours66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Bilansparcours66.exportcsv' => array(
				'departement' => array( 66 ),
				'modelName' => 'Bilanparcours66',
				'modelRechercheName' => 'WebrsaRechercheBilanparcours66',
				'component' => 'WebrsaRecherchesBilansparcours66New',
				'keys' => array( 'results.fields' )
			),
			'Contratsinsertion.cohorte_nouveaux' => array(
				'departement' => array( 93 ),
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionNouveau',
				'component' => 'WebrsaCohortesContratsinsertionNouveaux',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.cohorte_valides' => array(
				'type' => 'search',
				'departement' => array( 93 ),
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionValide',
				'component' => 'WebrsaCohortesContratsinsertionValides',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv_valides' => array(
				'departement' => array( 93 ),
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionValide',
				'component' => 'WebrsaCohortesContratsinsertionValides',
				'keys' => array( 'results.fields' )
			),
			'Contratsinsertion.search' => array(
				'modelName' => 'Contratinsertion',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv' => array(
				'modelName' => 'Contratinsertion',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields' )
			),
			'Contratsinsertion.search_valides' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaRechercheContratinsertionValides',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv_search_valides' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaRechercheContratinsertionValides',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields' )
			),
			'Contratsinsertion.cohorte_cersimpleavalider' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionCersimpleavalider',
				'component' => 'WebrsaCohortesContratsinsertionNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv_cersimpleavalider' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionCersimpleavalider',
				'component' => 'WebrsaCohortesContratsinsertionNew',
				'keys' => array( 'results.fields' )
			),
			'Contratsinsertion.cohorte_cerparticulieravalider' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionCersimpleavalider',
				'component' => 'WebrsaCohortesContratsinsertionNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv_cerparticulieravalider' => array(
				'departement' => 66,
				'modelName' => 'Contratinsertion',
				'modelRechercheName' => 'WebrsaCohorteContratinsertionCersimpleavalider',
				'component' => 'WebrsaCohortesContratsinsertionNew',
				'keys' => array( 'results.fields' )
			),
			'Cuis.search' => array(
				'departement' => 66,
				'modelName' => 'Cui',
				'component' => 'WebrsaRecherchesCuisNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Cuis.exportcsv' => array(
				'departement' => 66,
				'modelName' => 'Cui',
				'component' => 'WebrsaRecherchesCuisNew',
				'keys' => array( 'results.fields' )
			),
			'Defautsinsertionseps66.search_noninscrits' => array(
				'departement' => 66,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheNoninscrit',
				'component' => 'WebrsaRecherchesDefautsinsertionseps66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Defautsinsertionseps66.search_noninscrits' => array(
				'departement' => 66,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheSelectionradie',
				'component' => 'WebrsaRecherchesDefautsinsertionseps66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Demenagementshorsdpts.search' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDemenagementhorsdpt',
				'component' => 'WebrsaRecherchesDemenagementshorsdptsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Demenagementshorsdpts.exportcsv' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDemenagementhorsdpt',
				'component' => 'WebrsaRecherchesDemenagementshorsdptsNew',
				'keys' => array( 'results.fields' )
			),
			'Dossiers.search' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesDossiersNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossiers.exportcsv' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesDossiersNew',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.search' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaRechercheDossierpcg66',
				'component' => 'WebrsaRecherchesDossierspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaRechercheDossierpcg66',
				'component' => 'WebrsaRecherchesDossierspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.search_gestionnaire' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaRechercheDossierpcg66',
				'component' => 'WebrsaRecherchesDossierspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv_gestionnaire' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaRechercheDossierpcg66',
				'component' => 'WebrsaRecherchesDossierspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.cohorte_atransmettre' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Atransmettre',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv_atransmettre' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Atransmettre',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.cohorte_enattenteaffectation' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Enattenteaffectation',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv_enattenteaffectation' => array(
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Enattenteaffectation',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.cohorte_heberge' => array(
				'departement' => 66,
				'modelName' => 'Dossier',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Heberge',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv_heberge' => array(
				'departement' => 66,
				'modelName' => 'Dossier',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Heberge',
				'component' => 'WebrsaCohortesDossierspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Dossierspcgs66.cohorte_imprimer' => array(
				'type' => 'search',
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Imprimer',
				'component' => 'WebrsaCohortesDossierspcgs66Impressions',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossierspcgs66.exportcsv_imprimer' => array(
				'type' => 'search',
				'departement' => 66,
				'modelName' => 'Dossierpcg66',
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Imprimer',
				'component' => 'WebrsaCohortesDossierspcgs66Impressions',
				'keys' => array( 'results.fields' )
			),
			'Dsps.search' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDsp',
				'component' => 'WebrsaRecherchesDspsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dsps.exportcsv' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDsp',
				'component' => 'WebrsaRecherchesDspsNew',
				'keys' => array( 'results.fields' )
			),
			'Entretiens.search' => array(
				'modelName' => 'Entretien',
				'component' => 'WebrsaRecherchesEntretiensNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Entretiens.exportcsv' => array(
				'modelName' => 'Entretien',
				'component' => 'WebrsaRecherchesEntretiensNew',
				'keys' => array( 'results.fields' )
			),
			'Fichesprescriptions93.search' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheFicheprescription93',
				'component' => 'WebrsaRecherchesFichesprescriptions93New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Fichesprescriptions93.exportcsv' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheFicheprescription93',
				'component' => 'WebrsaRecherchesFichesprescriptions93New',
				'keys' => array( 'results.fields' )
			),
			'Indus.search' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesIndusNew',
				'modelRechercheName' => 'WebrsaRechercheIndu',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Indus.exportcsv' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesIndusNew',
				'modelRechercheName' => 'WebrsaRechercheIndu',
				'keys' => array( 'results.fields' )
			),
			'Nonorientationsproscovs58.cohorte' => array(
				'departement' => 58,
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaCohortesNonorientationsproscovs58New',
				'modelRechercheName' => 'WebrsaCohorteNonorientationprocov58',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Nonorientationsproscovs58.exportcsv' => array(
				'departement' => 58,
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaCohortesNonorientationsproscovs58New',
				'modelRechercheName' => 'WebrsaCohorteNonorientationprocov58',
				'keys' => array( 'results.fields' )
			),
			'Nonorientationsproseps.search' => array(
				'departement' => 66,
				'modelName' => 'Orientstruct',
				'modelRechercheName' => 'WebrsaRechercheNonorientationproep',
				'component' => 'WebrsaRecherchesNonorientationsprosepsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.cohorte_nouvelles' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteOrientstructNouvelle',
				'component' => 'WebrsaCohortesOrientsstructsNouvellesNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.cohorte_enattente' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteOrientstructEnattente',
				'component' => 'WebrsaCohortesOrientsstructsEnattenteNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.cohorte_orientees' => array(
				'type' => 'search',
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteOrientstructOrientees',
				'component' => 'WebrsaCohortesOrientsstructsImpressions',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.search' => array(
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaRecherchesOrientsstructsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.exportcsv' => array(
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaRecherchesOrientsstructsNew',
				'keys' => array( 'results.fields' )
			),
			'Propospdos.search' => array(
				'departement' => array( 58, 93, 976 ),
				'modelName' => 'Propopdo',
				'component' => 'WebrsaRecherchesPropospdosNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.exportcsv' => array(
				'departement' => array( 58, 93, 976 ),
				'modelName' => 'Propopdo',
				'component' => 'WebrsaRecherchesPropospdosNew',
				'keys' => array( 'results.fields' )
			),
			'Propospdos.search_possibles' => array(
				'departement' => array( 58, 93, 976 ),
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRecherchePropopdoPossible',
				'component' => 'WebrsaRecherchesPropospdosPossiblesNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.exportcsv_possibles' => array(
				'departement' => array( 58, 93, 976 ),
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRecherchePropopdoPossible',
				'component' => 'WebrsaRecherchesPropospdosPossiblesNew',
				'keys' => array( 'results.fields' )
			),
			'Propospdos.cohorte_nouvelles' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohortePropopdoNouvelle',
				'component' => 'WebrsaCohortesPropospdosNouvelles',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.cohorte_validees' => array(
				'type' => 'search',
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohortePropopdoValidee',
				'component' => 'WebrsaCohortesPropospdosValidees',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.exportcsv_validees' => array(
				'departement' => 93,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohortePropopdoValidee',
				'component' => 'WebrsaCohortesPropospdosValidees',
				'keys' => array( 'results.fields' )
			),
			'Rendezvous.search' => array(
				'modelName' => 'Rendezvous',
				'component' => 'WebrsaRecherchesRendezvousNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Rendezvous.exportcsv' => array(
				'modelName' => 'Rendezvous',
				'component' => 'WebrsaRecherchesRendezvousNew',
				'keys' => array( 'results.fields' )
			),
			'Sanctionseps58.cohorte_radiespe' => array(
				'departement' => 58,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteSanctionep58Radiepe',
				'component' => 'WebrsaCohortesSanctionseps58New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Sanctionseps58.exportcsv_radiespe' => array(
				'departement' => 58,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteSanctionep58Radiepe',
				'component' => 'WebrsaCohortesSanctionseps58New',
				'keys' => array( 'results.fields' )
			),
			'Sanctionseps58.cohorte_noninscritspe' => array(
				'departement' => 58,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteSanctionep58Noninscritpe',
				'component' => 'WebrsaCohortesSanctionseps58New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Sanctionseps58.exportcsv_noninscritspe' => array(
				'departement' => 58,
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaCohorteSanctionep58Noninscritpe',
				'component' => 'WebrsaCohortesSanctionseps58New',
				'keys' => array( 'results.fields' )
			),
			'Traitementspcgs66.search' => array(
				'departement' => array( 66 ),
				'modelName' => 'Traitementpcg66',
				'modelRechercheName' => 'WebrsaRechercheTraitementpcg66',
				'component' => 'WebrsaRecherchesTraitementspcgs66New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Traitementspcgs66.exportcsv' => array(
				'departement' => array( 66 ),
				'modelName' => 'Traitementpcg66',
				'modelRechercheName' => 'WebrsaRechercheTraitementpcg66',
				'component' => 'WebrsaRecherchesTraitementspcgs66New',
				'keys' => array( 'results.fields' )
			),
			'Transfertspdvs93.cohorte_atransferer' => array(
				'departement' => 93,
				'modelName' => 'Dossier',
				'modelRechercheName' => 'WebrsaCohorteTransfertpdv93Atransferer',
				'component' => 'WebrsaCohortesTransfertspdvs93Atransferer',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Transfertspdvs93.search' => array(
				'departement' => 93,
				'modelName' => 'Dossier',
				'modelRechercheName' => 'WebrsaRechercheTransfertpdv93',
				'component' => 'WebrsaRecherchesTransfertspdvs93New',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Transfertspdvs93.exportcsv' => array(
				'departement' => 93,
				'modelName' => 'Dossier',
				'modelRechercheName' => 'WebrsaRechercheTransfertpdv93',
				'component' => 'WebrsaRecherchesTransfertspdvs93New',
				'keys' => array( 'results.fields' )
			),
		);

		/**
		 * Live cache.
		 *
		 * Clés "config" et "query"
		 *
		 * @var array
		 */
		protected $_cache = array();

		protected function _loadCache() {
			if( $this->_cache === array() ) {
				$this->_cache = array();
				$this->_includeConfigFiles();

				$currentDepartement = (int)Configure::read( 'Cg.departement' );

					foreach( $this->searches as $key => $config ) {
					$departement = Hash::get( $config, 'departement' );
					if( $departement === null || in_array( $currentDepartement, (array)$departement ) ) {
						ClassRegistry::flush();//FIXME: vérifier
						$Recherches = $this->_component( $key, $config );

						$this->_cache[$key]['config'] = $Recherches->configureKeys( $config );
						$this->_cache[$key]['fields'] = $Recherches->checkConfiguredFields( $config );
						$this->_cache[$key]['query'] = $Recherches->checkQuery( $config );

						$type = Hash::get($config, 'type');
						if( ($type === null && strpos( $key, '.cohorte' ) !== false) || $type === 'cohorte' ) {
							$this->_cache[$key]['cohorte_values'] = $Recherches->checkHiddenCohorteValues( $config );
						}
					}
				}
			}
		}

		// @deprecated
		public function configureKeys() {
			$this->_loadCache();

			$result = array();
			foreach( $this->_cache as $cache ) {
				$result = array_merge( $result, $cache['config'] );
			}

			return $result;
		}

		public function checks() {
			$this->_loadCache();

			return $this->_cache;
		}

		/**
		 * Fonction utilitaire permettant de charger l'ensemble des fichiers de
		 * configuration se trouvant dans le répertoire du département connecté:
		 * app/Config/CgXXX (où XXX représente le n° du département)
		 *
		 * @param integer $departement
		 */
		protected function _includeConfigFiles() {
			$path = APP.'Config'.DS.'Cg'.Configure::read( 'Cg.departement' );

			$Dir = new Folder( $path );
			foreach( $Dir->find( '.*\.php' ) as $file ) {
				include_once $path.DS.$file;
			}
		}

		protected function _component( $key, array $config ) {
			list($controllerName, $actionName) = explode( '.', $key );
			$url = array( 'controller' => Inflector::underscore( $controllerName ), 'action' => Inflector::underscore( $actionName) );
			$Request = new CakeRequest( "{$url['controller']}/{$url['action']}", false );
			$Request->addParams( $url );

			$Controller = new AppController( $Request, new CakeResponse() );

			$Controller->name = $controllerName;
			$Controller->action = $actionName;
			$Controller->modelClass = $config['modelName'];
			$Controller->uses = array( $Controller->modelClass, Inflector::classify( $controllerName), $config['modelName'], 'Jeton', 'User' );
			$Controller->components = array( 'Session', 'Jetons2', 'InsertionsAllocataires', 'Gestionzonesgeos', 'Cohortes' );
			$Controller->helpers = array(
				'Default3' => array(
					'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
				)
			);

			$Controller->constructClasses();

			// TODO: une boucle ?
			$Controller->Jetons2->initialize( $Controller );
			$Controller->Cohortes->initialize( $Controller );

			$config += array( 'configurableQueryFieldsKey' => $key );
			$Recherches = $Controller->Components->load( $config['component'] );

			return $Recherches;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$currentDepartement = (int)Configure::read( 'Cg.departement' );
			$this->_includeConfigFiles();

			foreach( $this->searches as $key => $config ) {
				$departement = Hash::get( $config, 'departement' );
				$type = Hash::get( $config, 'type' );

				if( $departement === null || in_array( $currentDepartement, (array)$departement ) ) {
					// INFO: ajout d'une condition supplémentaire afin de ne pas avoir de résultats
					Configure::write( "ConfigurableQuery.{$key}.query.conditions", '0 = 1' );
					Configure::write( "ConfigurableQuery.{$key}.auto", false );

					echo "\t{$key}\n";
					ClassRegistry::flush();
					$Recherches = $this->_component( $key, $config );

					if( ($type === null && strpos( $key, '.search' ) !== false) || $type === 'search' ) {
						// INFO: on triche pour prétendre que le formulaire a bien été envoyé
						$Controller = $Recherches->_Collection->getController();
						$Controller->request->data = array( 'Search' => array( 'active' => 1 ) );
						$Recherches->search( $config );
					}
					else if( ($type === null && strpos( $key, '.cohorte' ) !== false) || $type === 'cohorte' ) {
						// INFO: on triche pour prétendre que le formulaire a bien été envoyé
						$Controller = $Recherches->_Collection->getController();
						$Controller->request->data = array( 'Search' => array( 'active' => 1 ) );
						$Recherches->cohorte( $config, array() );
					}
					else {
						$Recherches->exportcsv( $config );
					}
				}
			}

			return true;
		}
	}
?>