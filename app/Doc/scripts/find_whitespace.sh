#!/bin/bash

# Petit script bash permettant de détecter les fichiers .php, .inc et .ctp
# contenant uniquement des espaces blancs après la balise fermante php (?>).
# Nécessite le programme pcregrep
# @link http://www.johnnyslink.com/technology/2012/06/15/use-grep-find-php-files-white-space

PCREGREP_PRESENT="`which pcregrep > /dev/null ; echo $?`"

if [ "$PCREGREP_PRESENT" != "0" ] ; then
	echo "Ce script nécessite le programme pcregrep."
	echo "Pour l'installer sur Ubuntu: sudo aptitude install pcregrep"
	exit 1
fi

ME="$0"
APP_DIR="`dirname "$ME"`/../.."

find "$APP_DIR" -type f -iregex '.*\.\(php\|inc\|ctp\)' \! -path \*/\.svn/\* | while read -r ;
do
	pcregrep -Ml '\?>\s\s+\z' "$REPLY"
done