#!/bin/bash

tables=( \
	'accoemplois' \
	'decisionsreorient' \
	'demandesreorient' \
	'demandesreorient_sceanceseps' \
	'demandesreorient_seanceseps' \
	'difdisps' \
	'diflogs' \
	'difsocs' \
	'dspfs' \
	'dspfs_diflogs' \
	'dspfs_nataccosocfams' \
	'dspps' \
	'dspps_accoemplois' \
	'dspps_difdisps' \
	'dspps_difsocs' \
	'dspps_nataccosocindis' \
	'dspps_natmobs' \
	'dspps_nivetus' \
	'eps' \
	'eps_partseps' \
	'eps_zonesgeographiques' \
	'fonctionspartseps' \
	'motifsdemsreorients' \
	'nataccosocfams' \
	'nataccosocindis' \
	'natmobs' \
	'nivetus' \
	'partseps' \
	'partseps_sceanceseps' \
	'partseps_seanceseps' \
	'rolespartseps' \
	'sceanceseps' \
	'sceanceseps_demandesreorient' \
	'seanceseps' \
	'seanceseps_demandesreorient' \
)

for table in "${tables[@]}" ; do :
	files=`find \
			/home/cbuffin/www/webrsa/poc_optim1/app \
			-iname "*$table*" \
			-exec echo {} \; \
			| grep -v "\.svn" \
			| grep -v "repsd" \
			| grep "\/\(controllers\|views\)\/"`

	for path in ${files[@]} ; do :
		if [ "$path" != "" ] ; then

			if [ -f "$path" ]  ; then
				rm "$path"
			fi

			if [ -d "$path" ]  ; then
				rm -Rvf "$path"
			fi
		fi
	done
done
