#!/bin/bash
ME="$0"
APP_DIR="`dirname "$ME"`"
WEBRSADIR="/www/webrsa"
 # Ajouter autant de tables que l'on veut dumper : précédée du -t
TABLE="dossiers_rsa -t acos -t aros -t aros_acos -t users -t structuresreferentes"

# on dump seulement les tables qui nous intéressent
function onlyuseful(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -d -t $TABLE webrsa > $APP_DIR/$WEBRSADIR/test.sql
}

# on dump seulement les données des tables qui nous intéressent
function onlydata(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -a -d -t $TABLE webrsa > $APP_DIR/$WEBRSADIR/webrsa_data.sql
}

# on dump seulement le schéma des tables qui nous intéressent
function onlyschema(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -s -d -t $TABLE webrsa > $APP_DIR/$WEBRSADIR/webrsa_schema.sql
}

# on dump toute la base
function alldatabase(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -d webrsa > $APP_DIR/$WEBRSADIR/webrsaDataBase.sql
}

# on restaure la partie de la base que nous avons dumpé précédemment
function restore(){
    pg_restore -h localhost -p 5432 -U webrsa -d webrsa_test /home/aauzolat/$WEBRSADIR/test.dump #$APP_DIR/$WEBRSADIR/test.sql
}


case $1 in
    alldatabase)
        alldatabase
    ;;
    onlydata)
        onlydata
    ;;
    onlyusefull)
        onlyusefull
    ;;
    onlyschema)
        onlyschema
    ;;
    restore)
        restore
    ;;
    *)
        echo "Usage: $ME {alldatabase|onlyuseful|onlydata|onlyschema|restore}"
        exit 1
    ;;
esac