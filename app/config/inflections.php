<?php
/* SVN FILE: $Id: inflections.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Custom Inflected Words.
 *
 * This file is used to hold words that are not matched in the normail Inflector::pluralize() and
 * Inflector::singularize()
 *
 * PHP versions 4 and %
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 1.0.0.2312
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * This is a key => value array of regex used to match words.
 * If key matches then the value is returned.
 *
 *  $pluralRules = array('/(s)tatus$/i' => '\1\2tatuses', '/^(ox)$/i' => '\1\2en', '/([m|l])ouse$/i' => '\1ice');
 */
	$pluralRules = array();
/**
 * This is a key only array of plural words that should not be inflected.
 * Notice the last comma
 *
 * $uninflectedPlural = array('.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox');
 */
	$uninflectedPlural = array(
        'avispcgdroitrsa',
        'recours',
        'rendezvous',
        'parcours',
        'permisb',
        'avisref'
    );
/**
 * This is a key => value array of plural irregular words.
 * If key matches then the value is returned.
 *
 *  $irregularPlural = array('atlas' => 'atlases', 'beef' => 'beefs', 'brother' => 'brothers')
 */
    $irregularPlural = array(
        'modecontact'  => 'modescontact',
        'adressefoyer' => 'adressesfoyers',
        'adresse_foyer' => 'adresses_foyers',
        'titre_sejour' => 'titres_sejour',
        'contratinsertion' => 'contratsinsertion',
        'structurereferente' => 'structuresreferentes',
        'typocontrat' => 'typoscontrats',
        'orientstruct' => 'orientsstructs',
        'typeorient' => 'typesorients',
        'aidedirecte' => 'aidesdirectes',
        'aide_liee' => 'aides_liees',
        'actioninsertion' => 'actionsinsertion',
        'prestform' => 'prestsform',
        'typeaide' => 'typesaides',
        'refpresta' => 'refsprestas',
        'presta_lie' => 'presta_lies',
        'refpresta_lie' => 'refspresta_lies',
        'typeaction' => 'typesactions',
        'ressourcemensuelle' => 'ressourcesmensuelles',
        'ressourcetrimestrielle' => 'ressourcestrimestrielles',
        'detailressourcemensuelle' => 'detailsressourcesmensuelles',
        'infofinanciere' => 'infosfinancieres',
        'identificationflux' => 'identificationsflux',
        'totalisationacompte' => 'totalisationsacomptes',
        'dossiersimplifie' => 'dossierssimplifies',
        'suiviinstruction' => 'suivisinstruction',
        'suspensionversement' => 'suspensionsversements',
        'situationdossierrsa' => 'situationsdossiersrsa',
        'suspensiondroit' => 'suspensionsdroits',
        'condadmin' => 'condsadmins',
        'reducrsa' => 'reducsrsa',
        'infoagricole' => 'infosagricoles',
        'aideagricole' => 'aidesagricoles',
        'informationeti' => 'informationseti',
        'detaildroitrsa' => 'detailsdroitsrsa',
        'detailcalculdroitrsa' => 'detailscalculsdroitsrsa',
        'zonegeographique' => 'zonesgeographiques',
        'serviceinstructeur' => 'servicesinstructeurs',
        'foyercreance' => 'foyerscreances',
        'dossiercaf' => 'dossierscaf',
        'critereci' => 'criteresci',
        'regroupementzonegeo' => 'regroupementszonesgeo',
        'cohorteci' => 'cohortesci',
        'cohorteindu' => 'cohortesindus',
        // Infos financières
        'allocation_comptabilisee' => 'allocations_comptabilisees',
        'indu_constate' => 'indus_constates',
        'indu_transfere_cg' => 'indus_transferes_cg',
        'remise_indu' => 'remises_indus',
        'annulation_faible_montant' => 'annulations_faible_montant',
        'autre_annulation' => 'autres_annulations',
        //Parcours d'insertion
        'suiviinsertion' => 'suivisinsertion',
        'dossierpdo' => 'dossierspdo',
        'avispcgpersonne' => 'avispcgpersonnes',
        'indicateurmensuel' => 'indicateursmensuels',
        'cohortepdo' => 'cohortespdos',
        'propopdo' => 'propospdos',
        'critererdv' => 'criteresrdv',
        'typerdv' => 'typesrdv',
        'transmissionflux' => 'transmissionsflux',
        'suiviappuiorientation' => 'suivisappuisorientation',
        'calculdroitrsa' => 'calculsdroitsrsa',
        //PDOS
        'typenotifpdo' => 'typesnotifspdos',
        'decisionpdo' => 'decisionspdos',
        'typepdo' => 'typespdos',
        'piecepdo' => 'piecespdos',
        'propopdo_typenotifpdo' => 'propospdos_typesnotifspdos',
        'statutrdv' => 'statutsrdvs',
        'infopoleemploi' => 'infospoleemploi',
		// Apre
		'integrationfichierapre' => 'integrationfichiersapre',
        'pieceapre' => 'piecesapre',
        'apre_pieceapre' => 'apres_piecesapre',
        'apre_pieceliee' => 'apres_piecesliees',
        'apre_etatliquidatif' => 'apres_etatsliquidatifs',
        'domiciliationbancaire' => 'domiciliationsbancaires',
        ///->Formqualif
        'formqualif' => 'formsqualifs',
        'pieceformqualif' => 'piecesformsqualifs',
        'formqualif_pieceformqualif' => 'formsqualifs_piecesformsqualifs',
        ///->Actprof
        'actprof' => 'actsprofs',
        'pieceactprof' => 'piecesactsprofs',
        'actprof_pieceactprof' => 'actsprofs_piecesactsprofs',
        ///->Permisb
        'piecepermisb' => 'piecespermisb',
        'permisb_piecepermisb' => 'permisb_piecespermisb',
        ///->Amenaglogt
        'amenaglogt' => 'amenagslogts',
        'pieceamenaglogt' => 'piecesamenagslogts',
        'amenaglogt_pieceamenaglogt' => 'amenagslogts_piecesamenagslogts',
        ///->Acccreaentr
        'acccreaentr' => 'accscreaentr',
        'pieceacccreaentr' => 'piecesaccscreaentr',
        'acccreaentr_pieceacccreaentr' => 'accscreaentr_piecesaccscreaentr',
        ///->Acqmatprof
        'acqmatprof' => 'acqsmatsprofs',
        'pieceacqmatprof' => 'piecesacqsmatsprofs',
        'acqmatprof_pieceacqmatprof' => 'acqsmatsprofs_piecesacqsmatsprofs',
        ///->Locvehicinsert
        'locvehicinsert' => 'locsvehicinsert',
        'piecelocvehicinsert' => 'pieceslocsvehicinsert',
        'locvehicinsert_piecelocvehicinsert' => 'locsvehicinsert_pieceslocsvehicinsert',
        ///->Formpermfimo
        'formpermfimo' => 'formspermsfimo',
        'pieceformpermfimo' => 'piecesformspermsfimo',
        'formpermfimo_pieceformpermfimo' => 'formspermsfimo_piecesformspermsfimo',

        'montantconsomme' => 'montantsconsommes',
        'critereapre' => 'criteresapres',
        'repddtefp' => 'repsddtefp',
        ///Comité examen d'APRE
        'criterecomiteapre' => 'criterescomitesapres',
        'comiteapre' => 'comitesapres',
        'participantcomite' => 'participantscomites',
        'relanceapre' => 'relancesapres',
        'comiteapre_participantcomite' => 'comitesapres_participantscomites',
        'apre_comiteapre' => 'apres_comitesapres',
        'cohortecomiteapre' => 'cohortescomitesapres',
        ///Tiers prestatire lié à l'APRE
        'tiersprestataireapre' => 'tiersprestatairesapres',
        ///Recours pour les APREs
        'recoursapre' => 'recoursapres',
		/// Paiement foyer
		'paiementfoyer' => 'paiementsfoyers',
		'etatliquidatif' => 'etatsliquidatifs',
		'budgetapre' => 'budgetsapres',
		'parametrefinancier' => 'parametresfinanciers',
        ///Personnes chargées du suivi des aides APREs
        'suiviaideapre' => 'suivisaidesapres',
        'suiviaideapretypeaide' => 'suivisaidesaprestypesaides',
        'personne_referent' => 'personnes_referents',
        'jetonfonction' => 'jetonsfonctions',
        ///Informations complémentaires sur la personne
        'infocomplementaire' => 'infoscomplementaires',
        'creancealimentaire' =>'creancesalimentaires',
        'allocationsoutienfamilial' => 'allocationssoutienfamilial',
        'foyer_evenement' => 'foyers_evenements',
        ///Informations pour les fiches de candidatures
        'actioncandidat' => 'actionscandidats',
        'actioncandidat_personne' => 'actionscandidats_personnes',
        'contactpartenaire' => 'contactspartenaires',
        'contactpartenaire_partenaire' => 'contactspartenaires_partenaires',
        'actioncandidat_partenaire' => 'actionscandidats_partenaires',
        ///PDOs
        'originepdo' => 'originespdos',
        'statutpdo' => 'statutspdos',
        'situationpdo' => 'situationspdos',
        'propopdo_situationpdo' => 'propospdos_situationspdos',
        'propopdo_statutpdo' => 'propospdos_statutspdos',
        'statutdecisionpdo' => 'statutsdecisionspdos',
        'propopdo_statutdecisionpdo' => 'propospdos_statutsdecisionspdos',
		/// Équipes pluridisciplinaires
// 		'partep' => 'partseps',
// 		'rolepartep' => 'rolespartseps',
// 		'ep_partep' => 'eps_partseps',
// 		// Demandes de réorientation
// 		'motifdemreorient' => 'motifsdemsreorients',
// 		'demandereorient' => 'demandesreorient',
// 		'precoreorient' => 'precosreorients',
// 		// Détection des parcours
// // 		'parcoursdetecte' => 'parcoursdetectes',
// 		'decisionparcours' => 'decisionsparcours',
//         	'ep_theme' => 'eps_themes',
		/// Table de connexion ajoutée par gaëtan
		'aro_aco' => 'aros_acos',
		/// Dsp, v 1
        'dspp_nivetu' => 'dspps_nivetus',
		/// Dsp, v 2
		'detaildifsoc' => 'detailsdifsocs',
		'detailaccosocfam' => 'detailsaccosocfams',
		'detailaccosocindi' => 'detailsaccosocindis',
		'detaildifdisp' => 'detailsdifdisps',
		'detailnatmob' => 'detailsnatmobs',
		'detaildiflog' => 'detailsdiflogs',

        ///Table pour l'APRE 66
        'aideapre66' => 'aidesapres66',
        'typeaideapre66' => 'typesaidesapres66',
        'themeapre66' => 'themesapres66',
        'pieceaide66' => 'piecesaides66',
        'typeaideapre66_pieceaide66' => 'typesaidesapres66_piecesaides66',
        'aideapre66_pieceaide66' => 'aidesapres66_piecesaides66',
        'fraisdeplacement66' => 'fraisdeplacements66',
        'criterecui' => 'criterescuis',
		/// Équipes pluridisciplinaires
		'fonctionpartep' => 'fonctionspartseps',
		'partep' => 'partseps',
		'ep_zonegeographique' => 'eps_zonesgeographiques',
		'sceanceep' => 'sceanceseps',
		'partep_sceanceep' => 'partseps_sceanceseps',
		'motifdemreorient' => 'motifsdemsreorients',
		'demandereorient' => 'demandesreorient',
		'sceanceep_demandereorient' => 'sceanceseps_demandesreorient',
		'decisionreorient' => 'decisionsreorient',

     );
/**
 * This is a key => value array of regex used to match words.
 * If key matches then the value is returned.
 *
 *  $singularRules = array('/(s)tatuses$/i' => '\1\2tatus', '/(matr)ices$/i' =>'\1ix','/(vert|ind)ices$/i')
 */
	$singularRules = array();
/**
 * This is a key only array of singular words that should not be inflected.
 * You should not have to change this value below if you do change it use same format
 * as the $uninflectedPlural above.
 */
	$uninflectedSingular = $uninflectedPlural;
/**
 * This is a key => value array of singular irregular words.
 * Most of the time this will be a reverse of the above $irregularPlural array
 * You should not have to change this value below if you do change it use same format
 *
 * $irregularSingular = array('atlases' => 'atlas', 'beefs' => 'beef', 'brothers' => 'brother')
 */
	$irregularSingular = array_flip($irregularPlural);
?>
