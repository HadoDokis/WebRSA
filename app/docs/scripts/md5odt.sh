#!/bin/bash
# Calcule le md5sum des fichiers .odt* d'un répertoire et de ses sous-répertoires

if [ ! -d "$1" ] ; then
	echo "Le répertoire $1 n'existe pas."
	exit 1
fi

BASE_DIR="$1"

files=`find \
		"$BASE_DIR" \
		-iname "*.odt*" \
		-exec echo {} \; \
		| grep -v "\.svn"`

for file in ${files[@]} ; do :
	md5sum "$file"
done