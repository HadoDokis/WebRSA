#!/bin/bash

ME="$0"
APP_DIR="`dirname "$ME"`"
WORK_DIR="$PWD"
ChangeLog="ChangeLog.txt"
ASNV="svn://svn.adullact.net/svnroot/webrsa"

# ------------------------------------------------------------------------------
# INFO: rgadr sur un char -> sed -i "s/<RGADR>\([1-3]\)<\/RGADR>/<RGADR>0\1<\/RGADR>/" XXX
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

function clearCache() {
	__clearDir "$APP_DIR/tmp/cache/"
}

# ------------------------------------------------------------------------------

function clearLogs() {
	__clearDir "$APP_DIR/tmp/logs/"
}

# ------------------------------------------------------------------------------

function changelog() {
    version=${1}
    dir=${2}
    (
        cd $dir

        ChangeLogTmp="$ChangeLog.tmp"

        svn log $ASNV > $ChangeLogTmp

        startrev=`svn ls --verbose $ASNV/tags | grep "$version" | sed -e 's/^ *//' | cut -d " " -f1`
        startline=`grep -n "^r$startrev" $ChangeLogTmp | cut -d ":" -f1`
        maxlines=`cat $ChangeLogTmp | wc -l`
        numlines=`expr $maxlines - $startline + 1`

        tail -n $numlines $ChangeLogTmp > $ChangeLog
        rm $ChangeLogTmp

        for tag in `svn ls --verbose $ASNV/tags | sort -r | awk '{ printf( "%s/%s ", $1, $6 ) }'`; do
            rev=`echo "$tag" | cut -d "/" -f1`
            tag=`echo "$tag" | cut -d "/" -sf2`

            sed -i "s/^r$rev/\n************************************************************************\n Version $tag\n************************************************************************\n\nr$rev /" $ChangeLog
        done
    )
}


# ------------------------------------------------------------------------------

# TODO: svn log app > log-20090716-10h52.txt
# http://svnbook.red-bean.com/en/1.5/svn.tour.history.html
# svn ls --verbose svn://svn.adullact.net/svnroot/webrsa/tags

function package() {
    version=${1}
    mkdir -p "$WORK_DIR/package/webrsa-$version" >> "/dev/null" 2>&1 && \
    (
        cd "$WORK_DIR/package/webrsa-$version" >> "/dev/null" 2>&1 && \
        # TODO: RC pour trunk
        # svn export svn://svn.adullact.net/svnroot/webrsa/trunk >> "/dev/null" 2>&1 && \
#         svn export svn://svn.adullact.net/svnroot/webrsa/tags/$version/app >> "/dev/null" 2>&1 && \
        svn export $ASNV/tags/$version/app >> "/dev/null" 2>&1 && \

        rm -f "app/config/database.php.default" && \
        mv "app/config/database.php" "app/config/database.php.default" && \
        mv "app/config/webrsa.inc" "app/config/webrsa.inc.default" && \
        mv "app/config/core.php" "app/config/core.php.default" && \
        echo -n "$version" > "app/VERSION.txt" && \
        sed -i "s/Configure::write *( *'debug' *, *[0-9] *) *;/Configure::write('debug', 0);/" "app/config/core.php.default" && \
        sed -i "s/Configure::write *( *'Cache\.disable' *, *[^)]\+ *) *;/Configure::write('Cache.disable', false);/" "app/config/core.php.default" && \
        sed -i "s/Configure::write *( *'CG\.cantons' *, *[^)]\+ *) *;/Configure::write('CG.cantons', false);/" "app/config/webrsa.inc.default"
    ) && \
    (
        cd "$WORK_DIR/package" >> "/dev/null" 2>&1 && \
        changelog "$version" "webrsa-$version/app" && \
        zip -o -r -m "$WORK_DIR/webrsa-$version.zip" "webrsa-$version" >> "/dev/null" 2>&1 && \
        rmdir "$WORK_DIR/package"
    ) && \
    echo $version
}

# ------------------------------------------------------------------------------

case $1 in
    clear)
        clearCache
        clearLogs
    ;;
    clearcache)
        clearCache
    ;;
    clearlogs)
        clearLogs
    ;;
    package)
        package $2 # FIXME vérification argument
    ;;
    *)
        echo "Usage: $ME {clearcache|clear|clearlogs|package}"
        exit 1
    ;;
esac