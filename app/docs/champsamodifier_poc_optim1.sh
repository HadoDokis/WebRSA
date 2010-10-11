#!/bin/bash
# Mettre les sources en conformité avec le schéma dû au patch 2.0rc12-optim1

#===============================================================================

# INFO: remplacements déjà effectués dans les fichiers présents
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/dossier_rsa_id/dossier_id/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/dossiers_rsa/dossiers/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/adresses_foyers/adressesfoyers/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/adresse_foyer/adressefoyer/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/titres_sejour/titressejour/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/TitreSejour/Titresejour/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/typesaidesapres66_piecesaides66/piecesaides66_typesaidesapres66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/typeaideapre66_pieceaide66/pieceaide66_typeaideapre66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/Typeaideapre66Pieceaide66/Pieceaide66Typeaideapre66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/typesaidesapres66_piecescomptables66/piecescomptables66_typesaidesapres66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/typeaideapre66_piececomptable66/piececomptable66_typeaideapre66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/Typeaideapre66Piececomptable66/Piececomptable66Typeaideapre66/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/AvispcgdroitrsaController/AvispcgdroitsrsaController/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/ =& ClassRegistry/ = ClassRegistry/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/details_droits_rsa_id/detaildroitrsa_id/g"
# find app -regex ".*\(php\|ctp\|po\)$" | grep -v "\.svn" | xargs sed -i "s/avis_pcg_id/avispcgdroitrsa_id/g"

#===============================================================================

# INFO: remplacements déjà effectués sur les noms de fichiers présents
# svn mv app/tests/cases/controllers/avispcgdroitrsa_controller.test.php app/tests/cases/controllers/avispcgdroitsrsa_controller.test.php
# svn mv app/views/avispcgdroitrsa app/views/avispcgdroitsrsa
# svn mv app/controllers/avispcgdroitrsa_controller.php app/controllers/avispcgdroitsrsa_controller.php

#===============================================================================

function grepLines() {
    for champ in $*; do
		result="`grep -nR --exclude="*\.svn*" --exclude="*\.sql"  "[^A-Z]$champ[^A-Z]" app/ \
			| grep -v "tests\/" \
			| grep -v "\/config\/sql\/" \
			| grep -v ".*\.sh" \
			| grep -v "\.svn" \
			| grep -iv "detail$champ" \
			| grep -iv "details$champ" \
			| grep -v ".*\.txt"`"
		if [ "$result" != "" ] ; then
			echo "---------------------------------------------------------------------"
			echo "$champ"
			echo "---------------------------------------------------------------------"
			echo "$result"
		fi
    done
}

# ------------------------------------------------------------------------------

function grepFilenames() {
	for champ in $*; do
		result="`find app -regex ".*$champ.*\.\(php\|ctp\|po\)$" \
			| grep -v "\.svn" \
			| grep -iv "detail$champ" \
			| grep -iv "details$champ"`"
		if [ "$result" != "" ] ; then
			echo "---------------------------------------------------------------------"
			echo "$champ"
			echo "---------------------------------------------------------------------"
			echo "---------------------------------------------------------------------"
			echo "$result"
		fi
	done
}

#===============================================================================

champsModifies=( \
    'dossier_rsa_id' \
    'details_droits_rsa_id' \
    'avis_pcg_id' \
    'acompte_rsa_id' \
)

echo "==============================================================================="
echo "Champs modifiés"
echo "==============================================================================="
grepLines "${champsModifies[@]}"

# ------------------------------------------------------------------------------

tablesModifiees=( \
    'adresses_foyers' \
    'adresse_foyer' \
    'AdresseFoyer' \
    'titres_sejour' \
    'titre_sejour' \
    'TitreSejour' \
    'ressourcesmensuelles_detailsressourcesmensuelles' \
    'ressourcemensuelle_detailressourcemensuelle' \
    'RessourcemensuelleDetailressourcemensuelle' \
    'typesaidesapres66_piecesaides66' \
    'typeaideapre66_pieceaide66' \
    'Typeaideapre66Pieceaide66' \
    'typesaidesapres66_piecescomptables66' \
    'typeaideapre66_piececomptable66' \
    'Typeaideapre66Piececomptable66' \
    'users_contratsinsertion' \
    'user_contratinsertion' \
    'UserContratinsertion' \
    'zonesgeographiques_regroupementszonesgeo' \
    'zonegeographique_regroupementzonegeo' \
    'ZonegeographiqueRegroupementzonegeo' \
#     'avispcgdroitrsa' \ # Seulement pour les noms de fichier
    'AvispcgdroitrsaController' \

)

echo "==============================================================================="
echo "Tables (modèles) modifiées"
echo "==============================================================================="
grepLines "${tablesModifiees[@]}"
grepFilenames "${tablesModifiees[@]}"

# ------------------------------------------------------------------------------

tablesSupprimees=( \
	# Dspp, Dspf et tables liées
	'dspfs_diflogs' \
	'diflogs' \
	'diflog' \
	'Diflog' \
	'dspfs_nataccosocfams' \
	'dspf_nataccosocfam' \
	'DspfNataccosocfam' \
	'dspfs' \
	'dspf' \
	'Dspf' \
	'dspps_accoemplois' \
	'dspp_accoemploi' \
	'DsppAccoemploi' \
	'dspps_difdisps' \
	'dspp_difdisp' \
	'DsppDifdisp' \
	'dspps_difsocs' \
	'dspp_difsoc' \
	'DsppDifsoc' \
	'dspps_nataccosocindis' \
	'dspp_nataccosocindi' \
	'DsppNataccosocindi' \
	'dspps_natmobs' \
	'dspp_natmob' \
	'DsppNatmob' \
	'dspps_nivetus' \
	'dspp_nivetu' \
	'DsppNivetu' \
	'dspps' \
	'Dspp' \
	'dspp' \
	'decisionsreorient' \
	'decisionreorient' \
	'Decisionreorient' \
	'sceanceseps_demandesreorient' \
	'sceanceep_demandereorient' \
	'SceanceepDemandereorient' \
	'demandesreorient_seanceseps' \
	'demandereorient_seanceep' \
	'DemandereorientSeanceep' \
	'demandesreorient' \
	'demandereorient' \
	'Demandereorient' \
	'motifsdemsreorients' \
	'motifdemreorient' \
	'Motifdemreorient' \
	'partseps_seanceseps' \
	'partep_seanceep' \
	'PartepSeanceep' \
	'partseps_sceanceseps' \
	'partep_sceanceep' \
	'PartepSceanceep' \
	'sceanceseps' \
	'sceanceep' \
	'Sceanceep' \
	'seanceseps' \
	'seanceep' \
	'Seanceep' \
	'eps_zonesgeographiques' \
	'ep_zonegeographique' \
	'EpZonegeographique' \
	'partseps' \
	'partep' \
	'Partep' \
	'fonctionspartseps' \
	'fonctionpartep' \
	'Fonctionpartep' \
	'rolespartseps' \
	'rolepartep' \
	'Rolepartep' \
	'eps_partseps' \
	'ep_partep' \
	'EpPartep' \
# 	'eps' \
# 	'ep' \
# 	'Ep' \
)

echo "==============================================================================="
echo "Tables supprimées"
echo "==============================================================================="
grepLines "${tablesSupprimees[@]}"
grepFilenames "${tablesSupprimees[@]}"