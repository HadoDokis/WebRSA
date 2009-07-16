#!/bin/bash

ME="$0"
APP_DIR="`dirname "$ME"`"
USERNAME="cbuffin"
WORK_DIR="$PWD"

# ------------------------------------------------------------------------------

function clearcache() {
    cd "$APP_DIR/tmp/cache/" && \
    find . -type f -not -path '*/.svn/*' -not -name "empty" | while read -r ; do rm "$REPLY"; done
}

# ------------------------------------------------------------------------------

function clearlogs() {
    (
        cd "$APP_DIR/tmp/logs/" && \
        find . -type f -not -path '*/.svn/*' -not -name "empty" | while read -r ; do echo -n "" > "$REPLY"; done
    )
}

# ------------------------------------------------------------------------------
# TODO: svn log app > log-20090716-10h52.txt
# http://svnbook.red-bean.com/en/1.5/svn.tour.history.html

function package() {
    version=${1}
    mkdir -p "$WORK_DIR/package/webrsa-$version" >> "/dev/null" 2>&1 && \
    (
        cd "$WORK_DIR/package/webrsa-$version" >> "/dev/null" 2>&1 && \
        # TODO: RC pour trunk
        # svn export svn+ssh://$USERNAME@svn.adullact.net/svnroot/webrsa/trunk >> "/dev/null" 2>&1 && \
        svn export svn+ssh://$USERNAME@svn.adullact.net/svnroot/webrsa/tags/$version/app >> "/dev/null" 2>&1 && \
        rm -f "app/config/database.php.default" && \
        mv "app/config/database.php" "app/config/database.php.default" && \
        mv "app/config/webrsa.inc" "app/config/webrsa.inc.default" && \
        mv "app/config/core.php" "app/config/core.php.default" && \
        echo -n "$version" > "app/VERSION.txt" && \
        sed -i "s/Configure::write *( *'debug' *, *[0-9] *) *;/Configure::write('debug', 0);/" "app/config/core.php.default" && \
        sed -i "s/Configure::write *( *'Cache\.disable' *, *[^)]\+ *) *;/Configure::write('Cache.disable', false);/" "app/config/core.php.default"
    ) && \
    (
        cd "$WORK_DIR/package" >> "/dev/null" 2>&1 && \
        zip -o -r -m "$WORK_DIR/webrsa-$version.zip" "webrsa-$version" >> "/dev/null" 2>&1 && \
        rmdir "$WORK_DIR/package"
    ) && \
    echo $version
}

# ------------------------------------------------------------------------------

case $1 in
    clear)
        clearcache
        clearlogs
    ;;
    clearcache)
        clearcache
    ;;
    clearlogs)
        clearlogs
    ;;
    package)
        package $2 # FIXME vérification argument
    ;;
    *)
        echo "Usage: $ME {clearcache|clear|clearlogs|package}"
        exit 1
    ;;
esac