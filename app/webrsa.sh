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

function package() {
    version=${1}
    mkdir -p "$WORK_DIR/package" >> "/dev/null" 2>&1 && \
    (
        cd "$WORK_DIR/package" >> "/dev/null" 2>&1 && \
        svn export svn+ssh://$USERNAME@svn.adullact.net/svnroot/webrsa/tags/$version >> "/dev/null" 2>&1 && \
        rm -f "$version/app/config/database.php.default" && \
        mv "$version/app/config/database.php" "$version/app/config/database.php.default" && \
        # TODO: mettre les bonnes valeurs de cache et de debug
        mv "$version/app/config/webrsa.inc" "$version/app/config/webrsa.inc.default" && \
        mv "$version/app/config/core.php" "$version/app/config/core.php.default" && \
        echo "$version" > "$version/app/VERSION.txt"
        # TODO/FIXME
#         sed "s/^ \*Configure::write\( \*'debug' \*, \*[0-9] \*\) \*;/    Configure::write( 'debug', 0 );/" "$version/app/config/core.php.default"
    ) && \
    (
        cd "$WORK_DIR/package/$version" >> "/dev/null" 2>&1 && \
        # FIXME: chemins
        zip -o -r -m "../../webrsa-$version.zip" app >> "/dev/null" 2>&1 && \
        cd "../.." && \
        rmdir "package/$version" && \
        rmdir "package"
    ) && \
    echo $version
}

# ------------------------------------------------------------------------------

case $1 in
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
        echo "Usage: $ME {clearcache|clearlogs|package}"
        exit 1
    ;;
esac