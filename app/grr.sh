#!/bin/bash

ME="$0"
APP_DIR="`dirname "$ME"`"
SCRIPT_NAME="`basename "$ME"`"

#===============================================================================

#	0 => affiche la liste des fichiers impactés pour tous les changements
#	1 => affiche la liste des fichiers impactés par changement

MODE=0

#-------------------------------------------------------------------------------

changements=( \
	'ETATIRSA' \
	'DRORSARMIANT' \
	'COUVSOC' \
	'LIBAUTRDIFSOC' \
	'ELOPERSDIFDISP' \
	'OBSTEMPLOIDIFDISP' \
	'LIBAUTRACCOSOCINDI' \
	'RAPPEMPLOIQUALI' \
	'RAPPEMPLOIFORM' \
	'PERMICONDUB' \
	'LIBAUTRPERMICONDU' \
	'MOYLOCO' \
	'NIVETU' \
	'ACCOEMPLOI' \
	'MOTIDEMRSA' \
	'ACCOSOCFAM' \
	'LIBAUTRACCOSOCFAM' \
	'LIBCOORACCOSOCFAM' \
	'NATLOG' \
	'LIBAUTRDIFLOG' \
	'DEMARLOG' \
	'NATACCOSOCFAM' \
	'DIFLOG' \
)

#===============================================================================

if [[ $MODE = "0" ]] ; then
	OUTPUT="$APP_DIR/fichiers.impactes.txt.tmp"
else
	OUTPUT="$APP_DIR/impacts.par.fichier.txt"
fi

echo "" > "$OUTPUT"

#===============================================================================

for changement in ${changements[*]}; do
	if [[ $MODE = "1" ]] ; then
		echo "================================================================================" >> "$OUTPUT"
		echo "$changement" >> "$OUTPUT"
		echo "--------------------------------------------------------------------------------" >> "$OUTPUT"
	fi

	grep -i -l -R "$changement" * \
		| grep -v "\/\.svn\/" \
		| grep -v "Fichier binaire " \
		| grep -v "vendors\/simpletest" \
		| grep -v "config\/sql\/" \
		| grep -v "INFO\.txt" \
		| grep -v "fichiers\.impactes.txt" \
		| grep -v "impacts\.par\.fichier\.txt" \
		| grep -v "$SCRIPT_NAME" >> "$OUTPUT"
done

#===============================================================================

if [[ $MODE = "0" ]] ; then
	cat "$OUTPUT" | sort -r | uniq > "$APP_DIR/fichiers.impactes.txt"
	rm "$APP_DIR/fichiers.impactes.txt.tmp"
	cat "$APP_DIR/fichiers.impactes.txt"
else
	cat "$OUTPUT"
fi