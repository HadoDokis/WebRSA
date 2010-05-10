#!/bin/bash

ME="$0"
APP_DIR="`dirname "$ME"`"
WORK_DIR="$PWD"
RELEASES_DIR="$WORK_DIR/releases"
ChangeLog="ChangeLog.txt"
ASNV="svn://svn.adullact.net/svnroot/webrsa"

# ------------------------------------------------------------------------------
# INFO: rgadr sur un char -> sed -i "s/<RGADR>\([1-3]\)<\/RGADR>/<RGADR>0\1<\/RGADR>/" XXX
# ------------------------------------------------------------------------------

function __svnDirExists() {
	svndir="$1"
	svn ls --verbose $svndir >> /dev/null 2>&1
	return=$?

	if [ $return -ne 0 ] ; then
		echo "Erreur: le répertoire $svndir n'existe pas"
	fi
	return $return
}

# ------------------------------------------------------------------------------

function __clearDir() {
	dir="$1"

	if [ -d "$dir" ]
	then
		(
			cd "$dir"
			find . -type f -not -path '*/.svn/*' -not -name "empty" | while read -r ; do rm "$REPLY"; done
		)
	fi
}

# ------------------------------------------------------------------------------

function __changelog() {
    version=${1}
    dir=${2}
    (
        cd $dir

        ChangeLogTmp="$ChangeLog.tmp"

        svn log $ASNV > $ChangeLogTmp

        startrev=`svn ls --verbose $ASNV/tags | grep " $version/" | sed -e 's/^ *//' | cut -d " " -f1`
        startline=`grep -n "^r$startrev" $ChangeLogTmp | cut -d ":" -f1`
        maxlines=`cat $ChangeLogTmp | wc -l`
        numlines=`expr $maxlines - $startline + 1`

        tail -n $numlines $ChangeLogTmp > $ChangeLog
        rm $ChangeLogTmp

        for tag in `svn ls --verbose $ASNV/tags | sed 's/^\W*\([0-9]\+\)\W\+.* \+\([^ ]\+\)\/$/\1 \2/g' | grep -v "^[0-9]\+ .$" | sort -n -r -k1 | sed 's/^\([^ ]\+\) \([^ ]\+\)$/\1\/\2/g'`; do
            rev=`echo "$tag" | cut -d "/" -f1`
            tag=`echo "$tag" | cut -d "/" -sf2`
            sed -i "s/^r$rev /\n************************************************************************\n Version $tag\n************************************************************************\n\nr$rev /" $ChangeLog
        done
    )
}


# ------------------------------------------------------------------------------

function __cleanFilesForRelease() {
    dir="$1"
    version="$2"

	cd "$dir"
	rm -f "app/config/database.php.default" >> /dev/null 2>&1
	mv "app/config/database.php" "app/config/database.php.default" >> /dev/null 2>&1
	mv "app/config/webrsa.inc" "app/config/webrsa.inc.default" >> /dev/null 2>&1
	mv "app/config/core.php" "app/config/core.php.default" >> /dev/null 2>&1
	(
		cd "app/vendors/modelesodt" && \
		find . -type f -iname "*.odt" | while read -r ; do mv "$REPLY" "$REPLY.default"; done
	)
	echo -n "$version" > "app/VERSION.txt"
	sed -i "s/Configure::write *( *'debug' *, *[0-9] *) *;/Configure::write('debug', 0);/" "app/config/core.php.default" >> /dev/null 2>&1
	sed -i "s/Configure::write *( *'Cache\.disable' *, *[^)]\+ *) *;/Configure::write('Cache.disable', false);/" "app/config/core.php.default" >> /dev/null 2>&1
	sed -i "s/Configure::write *( *'CG\.cantons' *, *[^)]\+ *) *;/Configure::write('CG.cantons', false);/" "app/config/webrsa.inc.default" >> /dev/null 2>&1
	sed -i "s/Configure::write *( *'Zonesegeographiques\.CodesInsee' *, *[^)]\+ *) *;/Configure::write('Zonesegeographiques.CodesInsee', true);/" "app/config/webrsa.inc.default" >> /dev/null 2>&1
}

# ------------------------------------------------------------------------------

# TODO: svn log app > log-20090716-10h52.txt
# http://svnbook.red-bean.com/en/1.5/svn.tour.history.html
# svn ls --verbose svn://svn.adullact.net/svnroot/webrsa/tags

function __package() {
    version=${1}
    mkdir -p "$RELEASES_DIR/webrsa-$version" >> "/dev/null" 2>&1 && \
    (
        cd "$RELEASES_DIR/webrsa-$version" >> "/dev/null" 2>&1 && \
        # TODO: RC pour trunk
        # svn export svn://svn.adullact.net/svnroot/webrsa/trunk >> "/dev/null" 2>&1 && \
#         svn export svn://svn.adullact.net/svnroot/webrsa/tags/$version/app >> "/dev/null" 2>&1 && \
        svn export $ASNV/tags/$version/app >> "/dev/null" 2>&1 && \

		__cleanFilesForRelease "$RELEASES_DIR/webrsa-$version" "$version"
    ) && \
    (
        cd "$RELEASES_DIR" >> "/dev/null" 2>&1 && \
        __changelog "$version" "$RELEASES_DIR/webrsa-$version/app" && \
        zip -o -r -m "$RELEASES_DIR/webrsa-$version.zip" "webrsa-$version" >> "/dev/null" 2>&1
    ) && \
    echo $version
}

# ------------------------------------------------------------------------------

function __patch() {
	REFERENCE="$1"
	TRUNK="$2"

	REFERENCE=`echo $REFERENCE | sed 's/\/$//'`
	TRUNK=`echo $TRUNK | sed 's/\/$//'`

	versionReference=`basename $REFERENCE`
	versionTrunk=`basename $TRUNK`

	NAME="patch-webrsa-$versionReference-$versionTrunk"
	DESTINATION="$RELEASES_DIR/$NAME"

	mkdir -p "$DESTINATION" >> /dev/null


	REFERENCE_ESCAPED=`echo $REFERENCE | sed 's/\//\\\\\//g'`

	for serverfile in `svn diff "$REFERENCE" "$TRUNK" --summarize | grep -e '^M ' -e '^A ' -e '^AM ' | sed 's/^\(A\|M\|AM\) \+//'`; do
		file=`echo $serverfile | sed "s/"$REFERENCE_ESCAPED"\///"`
		dir=`dirname $file`
		mkdir -p "$DESTINATION/$dir" >> "/dev/null" 2>&1
		svn export "$TRUNK/$file" "$DESTINATION/$file" >> "/dev/null" 2>&1
	done

	__cleanFilesForRelease "$DESTINATION" "$versionTrunk"
	__changelog "$versionTrunk" "$DESTINATION/app"
	cd "$DESTINATION/.."
	zip -o -r -m "$DESTINATION.zip" "$NAME" >> "/dev/null" 2>&1
}

# ------------------------------------------------------------------------------

case $1 in
    changelog)
		__svnDirExists "$ASNV/tags/$2"
		existsDir=$?
		if [[ $existsDir -ne 0 ]] ; then
			exit 1
		fi
        __changelog "$2" .
    ;;
    clear)
        __clearDir "$APP_DIR/tmp/cache/"
        __clearDir "$APP_DIR/tmp/logs/"
		if [ -d "$APP_DIR/tmp/files/" ] ; then
			rm -R "$APP_DIR/tmp/files/"
		fi
    ;;
    clearcache)
        __clearDir "$APP_DIR/tmp/cache/"
    ;;
    clearlogs)
        __clearDir "$APP_DIR/tmp/logs/"
    ;;
    package)
		# Vérification de l'argument
		__svnDirExists "$ASNV/tags/$2"
		if [[ $? -ne 0 ]] ; then
			exit 1
		fi

        __package $2
    ;;
    patch)
		# Vérification des arguments
		__svnDirExists "$ASNV/$2"
		existsDir1=$?
		__svnDirExists "$ASNV/$3"
		existsDir2=$?
		if [[ $existsDir1 -ne 0 || $existsDir2 -ne 0 ]] ; then
			exit 1
		fi
		# ex: app/webrsa.sh patch tags/1.0.9 branches/1.0.8
        __patch "$ASNV/$2" "$ASNV/$3"
    ;;
    *)
        echo "Usage: $ME {changelog|clearcache|clear|clearlogs|package|patch}"
        exit 1
    ;;
esac

#  Afin d'enlever l'extension defualt des fichiers ODT sans avoir à le faire à la main
#         (
#             cd "app/vendors/modelesodt" && \
#             find . -type f -iname "*.odt.default" | while read -r ; do mv "$REPLY" `echo "$REPLY" |sed 's/\.default$//g'` ; done
#         )