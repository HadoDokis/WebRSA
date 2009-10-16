#!/bin/bash

ME="$0"

SQL_DIR="`dirname "$ME"`/../.."
# SQL_DIR="/home/cbuffin/projets/htdocs/adullact/webrsa/branche-1.0.8/app/config/sql"

WORK_DIR="$PWD"

DBNAME="webrsa_v2"
# DBNAME="webrsa"

USERNAME="webrsa"

# FIXME ne devoir rentrer qu'une seule fois le mot de passe
(
#     psql -c "DROP DATABASE $DBNAME;"
    psql -c "CREATE DATABASE $DBNAME OWNER webrsa ENCODING 'utf8';"
    SCRIPTS=( \
        'webrsa.SCHEMA-20090525.sql' \
        'webrsa.DATA-20090525.sql' \
        'patches/1.x/patch-version1.0.1-20090612.17h.sql' \
        'patches/1.x/patch-version1.0.2-20090616.17h06.sql' \
        'patches/1.x/patch-version1.0.3-20090623.18h08.sql' \
        'patches/1.x/patch-version1.0.4-20090713.15h45.sql' \
        'patches/1.x/patch-version1.0.5-20090724.12h30.sql' \
        'patches/1.x/patch-version1.0.6-20090726-16h41.sql' \
        'patches/1.x/patch-version1.0.7-20090820-15h19.sql' \
        'patches/1.x/patch-version1.0.8-20090907-11h31.sql' \
        'patches/1.x/patch-version1.0.8.1-20090914-16h20.sql' \
        'patches/1.x/patch-version1.0.8.2-20090918-14h48.sql' \
        'patches/1.x/patch_mise_a_niveau_des_id.sql' \
        'patches/1.x/patch_script_index_table.sql' \
        'patches/2.x/patch-version2.0-20091005-17h44.sql' \
    )

    for SCRIPT in ${SCRIPTS[*]}; do
        psql -U $USERNAME -W -d $DBNAME -f "$SQL_DIR/$SCRIPT"
    done
)