#!/bin/bash

OLDVERSION="1.0.3"
VERSION="1.0.4"
TMP=`mktemp -d /tmp/WEBRSA-XXXXXX`
URL="http://adullact.net/frs/download.php/4581/webrsa-$VERSION.zip"
USER="webrsa"
WEBRSADIR="/var/www/webrsa"

# ------------------------------------------------------------------------------

if [ $UID -ne 0 ]; then
    echo " Must be run by root"
    exit 0
fi

if [ ! -x /usr/bin/unzip ]; then
    echo "You must install unzip EOF"
    exit 0;
elif [ ! -x /usr/bin/wget ]; then
    echo "You must install wget EOF"
    exit 0;
fi

if [ $UID -ne 0 ]; then
    echo " Must be run by root"
    exit 0
fi

DOCROOT="/var/www"

# ------------------------------------------------------------------------------

# function addcron() {
#     if [ ! -e /etc/cron.d/webrsa ]; then
#         echo "PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin" >> /etc/cron.d/webrsa
#         echo "" >> /etc/cron.d/webrsa
#         echo "15 10 * * * $USER $WEBRSADIR/cake/console/cake refresh -app $WEBRSADIR/app >> $WEBRSADIR/app/tmp/logs/cron.log" >> /etc/cron.d/webrsa
#     fi
# }

# ------------------------------------------------------------------------------

if [ -d $DOCROOT/webrsa ] ; then
    if wget $URL -O $TMP/webrsa-$VERSION.zip; then
        unzip $TMP/webrsa-$VERSION.zip -d $TMP/
        cp -r $DOCROOT/webrsa $DOCROOT/webrsaold-$OLDVERSION
        rm -r $DOCROOT/webrsa/app
        cp -r $TMP/webrsa-$VERSION/app $DOCROOT/webrsa/
        cp $DOCROOT/webrsaold-$OLDVERSION/app/config/database.php $DOCROOT/webrsa/app/config/
        cat $DOCROOT/webrsa/app/config/webrsa.inc.default |sed "s/gedooo.services.adullact.org/gedooo.cg93.fr/g" > $DOCROOT/webrsa/app/config/webrsa.inc
        OLDSALT=`cat $DOCROOT/webrsaold-$OLDVERSION/app/config/core.php|grep Security.salt|cut -d"'" -f4`
        cat $DOCROOT/webrsa/app/config/core.php.default|sed "s/AYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi/$OLDSALT/g" > $DOCROOT/webrsa/app/config/core.php
        chown -R www-data: $DOCROOT/webrsa
    fi
else
    echo "Veuillez installer webrsa, ceci est un script de mise a jour"
    echo "Fin du script"
fi

# ------------------------------------------------------------------------------

rm -r $TMP