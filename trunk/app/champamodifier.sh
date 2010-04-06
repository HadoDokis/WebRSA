#!/bin/bash

# TODO: les modèles de documents ODT

#===============================================================================

function grepLines() {
    for champ in $*; do
        echo "---------------------------------------------------------------------"
        echo "$champ"
        echo "---------------------------------------------------------------------"
        grep -nRi --exclude="*\.svn*" --exclude="*\.sql"  "[^A-Z]$champ[^A-Z]" app/ | grep -v "tests\/" 
    done
}

#===============================================================================

champsModifies=( \
    # Champs Suiviinstruction
    'ETATIRSA' \
    # Champs Dspp
    'DRORSARMIANT' \
    'COUVSOC' \
    'LIBAUTRDIFSOC' \
    'MOYLOCO' \
)

#-------------------------------------------------------------------------------


champsSupprimes=( \
    # Table + Champs Dspp
    'Dspp' \
    'dspps' \
    'ELOPERSDIFDISP' \
    'OBSTEMPLOIDIFDISP' \
    'LIBAUTRACCOSOCINDI' \
    'RAPPEMPLOIQUALI' \
    'RAPPEMPLOIFORM' \
    'PERMICONDUB' \
    'LIBAUTRPERMICONDU' \
    # Table Nivetu
    'NIVETU' \
    # Table Accoemploi
    'ACCOEMPLOI' \
    # Table + Champs Dspf
    'Dspf' \
    'dspfs' \
    'MOTIDEMRSA' \
    'ACCOSOCFAM' \
    'LIBAUTRACCOSOCFAM' \
    'LIBCOORACCOSOCFAM' \
    'NATLOG' \
    'LIBAUTRDIFLOG' \
    'DEMARLOG' \
    # Table Nataccosocfam
    'NATACCOSOCFAM' \
    # Table Diflog
    'DIFLOG' \
)

#===============================================================================

echo "==============================================================================="
echo "Champs modifiés"
echo "==============================================================================="
grepLines "${champsModifies[@]}"

echo "==============================================================================="
echo "Champs supprimés"
echo "==============================================================================="
grepLines "${champsSupprimes[@]}"