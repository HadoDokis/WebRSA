<?php
/* SVN FILE: $Id$ */
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
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
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
// 	$uninflectedPlural = array(
// 		// Tables
// 		'bilanparcours' => 'bilanparcours',
// 		'parcours' => 'parcours',
// 		'permisb' => 'permisb',
// 		'rendezvous' => 'rendezvous',
// 	);

    $uninflectedPlural = array(
        'recours',
        'rendezvous',
        'parcours',
        'permisb',
        'avisref',
        'bilanparcours',
        //Harry     
        'rejet_historique',
        //Fin harry
    );
/**
 * This is a key => value array of plural irregular words.
 * If key matches then the value is returned.
 *
 *  $irregularPlural = array('atlas' => 'atlases', 'beef' => 'beefs', 'brother' => 'brothers')
 */
	$irregularPlural = array(
		// Tables
		'acccreaentr' => 'accscreaentr',
		'acccreaentr_pieceacccreaentr' => 'accscreaentr_piecesaccscreaentr',
		'acqmatprof' => 'acqsmatsprofs',
		'acqmatprof_pieceacqmatprof' => 'acqsmatsprofs_piecesacqsmatsprofs',
		'actioncandidat' => 'actionscandidats',
		'actioncandidat_partenaire' => 'actionscandidats_partenaires',
		'actioncandidat_personne' => 'actionscandidats_personnes',
		'actioninsertion' => 'actionsinsertion',
		'actprof' => 'actsprofs',
		'actprof_pieceactprof' => 'actsprofs_piecesactsprofs',
		'adresse' => 'adresses',
		'adressefoyer' => 'adressesfoyers',
		'aideagricole' => 'aidesagricoles',
		'aideapre66' => 'aidesapres66',
		'aideapre66_pieceaide66' => 'aidesapres66_piecesaides66',
		'aideapre66_piececomptable66' => 'aidesapres66_piecescomptables66',
		'aidedirecte' => 'aidesdirectes',
		'allocationsoutienfamilial' => 'allocationssoutienfamilial',
		'amenaglogt' => 'amenagslogts',
		'amenaglogt_pieceamenaglogt' => 'amenagslogts_piecesamenagslogts',
		'anomalie' => 'anomalies',
		'apre_comiteapre' => 'apres_comitesapres',
		'apre_etatliquidatif' => 'apres_etatsliquidatifs',
		'apre_pieceapre' => 'apres_piecesapre',
		'aro_aco' => 'aros_acos',
		'avispcgdroitrsa' => 'avispcgdroitsrsa',
		'avispcgpersonne' => 'avispcgpersonnes',
		'budgetapre' => 'budgetsapres',
		'calculdroitrsa' => 'calculsdroitsrsa',
		'cohorteci' => 'cohortesci',
		'cohortecomiteapre' => 'cohortescomitesapres',
		'cohorteindu' => 'cohortesindus',
		'cohortepdo' => 'cohortespdos',
		'comiteapre' => 'comitesapres',
		'comiteapre_participantcomite' => 'comitesapres_participantscomites',
		'condadmin' => 'condsadmins',
		'contactpartenaire' => 'contactspartenaires',
		'contactpartenaire_partenaire' => 'contactspartenaires_partenaires',
		'contratinsertion' => 'contratsinsertion',
		'contratinsertion_user' => 'contratsinsertion_users',
		'controleadministratif' => 'controlesadministratifs',
		'creancealimentaire' => 'creancesalimentaires',
		'critereapre' => 'criteresapres',
        'critereci' => 'criteresci',
		'criterecui' => 'criterescuis',
		'criterepdo' => 'criterespdos',
		'critererdv' => 'criteresrdv',
		'decisionparcours' => 'decisionsparcours',
		'decisionpdo' => 'decisionspdos',
		'descriptionpdo' => 'descriptionspdos',
		'detailaccosocfam' => 'detailsaccosocfams',
		'detailaccosocindi' => 'detailsaccosocindis',
		'detailcalculdroitrsa' => 'detailscalculsdroitsrsa',
		'detaildifdisp' => 'detailsdifdisps',
		'detaildiflog' => 'detailsdiflogs',
		'detaildifsoc' => 'detailsdifsocs',
		'detaildroitrsa' => 'detailsdroitsrsa',
		'detailnatmob' => 'detailsnatmobs',
        /// Dsp CG
        'detailmoytrans' => 'detailsmoytrans',
        'detaildifsocpro' => 'detailsdifsocpros',
        'detailprojpro' => 'detailsprojpros',
        'detailfreinform' => 'detailsfreinforms',
        'detailconfort' => 'detailsconforts',
        'dsp_rev' => 'dsps_revs',
        'detaildifsoc_rev' => 'detailsdifsocs_revs',
        'detailaccosocfam_rev' => 'detailsaccosocfams_revs',
        'detailaccosocindi_rev' => 'detailsaccosocindis_revs',
        'detaildifdisp_rev' => 'detailsdifdisps_revs',
        'detailnatmob_rev' => 'detailsnatmobs_revs',
        'detaildiflog_rev' => 'detailsdiflogs_revs',
        'detailmoytrans_rev' => 'detailsmoytrans_revs',
        'detaildifsocpro_rev' => 'detailsdifsocpros_revs',
        'detailprojpro_rev' => 'detailsprojpros_revs',
        'detailfreinform_rev' => 'detailsfreinforms_revs',
        'detailconfort_rev' => 'detailsconforts_revs',
        /// Fin DSP CG
		'detailressourcemensuelle' => 'detailsressourcesmensuelles',
		'detailressourcemensuelle_ressourcemensuelle' => 'detailsressourcesmensuelles_ressourcesmensuelles',
		'domiciliationbancaire' => 'domiciliationsbancaires',
		'dossiercaf' => 'dossierscaf',
        'dossiersimplifie' => 'dossierssimplifies',
		'etatliquidatif' => 'etatsliquidatifs',
		'formpermfimo' => 'formspermsfimo',
		'formpermfimo_pieceformpermfimo' => 'formspermsfimo_piecesformspermsfimo',
		'formqualif' => 'formsqualifs',
		'formqualif_pieceformqualif' => 'formsqualifs_piecesformsqualifs',
		'fraisdeplacement66' => 'fraisdeplacements66',
		'grossesse' => 'grossesses',
		'identificationflux' => 'identificationsflux',
        'indicateurmensuel' => 'indicateursmensuels',
		'informationeti' => 'informationseti',
		'infoagricole' => 'infosagricoles',
		'infofinanciere' => 'infosfinancieres',
		'infopoleemploi' => 'infospoleemploi',
		'integrationfichierapre' => 'integrationfichiersapre',
		'jetonfonction' => 'jetonsfonctions',
		'locvehicinsert_piecelocvehicinsert' => 'locsvehicinsert_pieceslocsvehicinsert',
		'locvehicinsert' => 'locsvehicinsert',
		'modecontact' => 'modescontact',
		'montantconsomme' => 'montantsconsommes',
		'orientstruct' => 'orientsstructs',
		'orientstruct_serviceinstructeur' => 'orientsstructs_servicesinstructeurs',
		'originepdo' => 'originespdos',
		'paiementfoyer' => 'paiementsfoyers',
		'parametrefinancier' => 'parametresfinanciers',
		'participantcomite' => 'participantscomites',
		'periodeimmersion' => 'periodesimmersion',
		'permisb_piecepermisb' => 'permisb_piecespermisb',
		'personne_referent' => 'personnes_referents',
		'pieceacccreaentr' => 'piecesaccscreaentr',
		'pieceacqmatprof' => 'piecesacqsmatsprofs',
		'pieceactprof' => 'piecesactsprofs',
		'pieceaide66' => 'piecesaides66',
		'pieceamenaglogt' => 'piecesamenagslogts',
		'pieceapre' => 'piecesapre',
		'piececomptable66' => 'piecescomptables66',
		'pieceformpermfimo' => 'piecesformspermsfimo',
		'pieceformqualif' => 'piecesformsqualifs',
		'piecelocvehicinsert' => 'pieceslocsvehicinsert',
		'piecepdo' => 'piecespdos',
		'piecepermisb' => 'piecespermisb',
		'precoreorient' => 'precosreorients',
		'prestform' => 'prestsform',
		'propopdo' => 'propospdos',
		'propopdo_situationpdo' => 'propospdos_situationspdos',
		'propopdo_statutdecisionpdo' => 'propospdos_statutsdecisionspdos',
		'propopdo_statutpdo' => 'propospdos_statutspdos',
        'recoursapre' => 'recoursapres',
		'reducrsa' => 'reducsrsa',
		'refpresta' => 'refsprestas',
		'regroupementzonegeo' => 'regroupementszonesgeo',
		'regroupementzonegeo_zonegeographique' => 'regroupementszonesgeo_zonesgeographiques',
		'relanceapre' => 'relancesapres',
        'repddtefp' => 'repsddtefp',
		'ressource_ressourcemensuelle' => 'ressources_ressourcesmensuelles',
		'ressourcemensuelle' => 'ressourcesmensuelles',
		'serviceinstructeur' => 'servicesinstructeurs',
		'situationdossierrsa' => 'situationsdossiersrsa',
		'situationpdo' => 'situationspdos',
		'statutdecisionpdo' => 'statutsdecisionspdos',
		'statutpdo' => 'statutspdos',
		'statutrdv' => 'statutsrdvs',
		'structurereferente_zonegeographique' => 'structuresreferentes_zonesgeographiques',
		'structurereferente' => 'structuresreferentes',
		'suiviaideapre' => 'suivisaidesapres',
		'suiviaideapretypeaide' => 'suivisaidesaprestypesaides',
		'suiviappuiorientation' => 'suivisappuisorientation',
		'suiviinsertion' => 'suivisinsertion',
		'suiviinstruction' => 'suivisinstruction',
		'suspensiondroit' => 'suspensionsdroits',
		'suspensionversement' => 'suspensionsversements',
		'themeapre66' => 'themesapres66',
		'tiersprestataireapre' => 'tiersprestatairesapres',
		'titresejour' => 'titressejour',
		'totalisationacompte' => 'totalisationsacomptes',
		'traitementpdo' => 'traitementspdos',
		'traitementtypepdo' => 'traitementstypespdos',
		'transmissionflux' => 'transmissionsflux',
		'typeaction' => 'typesactions',
		'typeaideapre66' => 'typesaidesapres66',
		'pieceaide66_typeaideapre66' => 'piecesaides66_typesaidesapres66',
		'piececomptable66_typeaideapre66' => 'piecescomptables66_typesaidesapres66',
		'typenotifpdo' => 'typesnotifspdos',
		'typeorient' => 'typesorients',
		'typepdo' => 'typespdos',
		'typerdv' => 'typesrdv',
		'typocontrat' => 'typoscontrats',
		'user_zonegeographique' => 'users_zonesgeographiques',
		'zonegeographique' => 'zonesgeographiques',
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
