#!/bin/bash
ME="$0"
APP_DIR="`dirname "$ME"`"
WEBRSADIR="/www/webrsa"

 # Ajouter autant de tables que l'on veut dumper : précédée du -t
TABLES_METIERS="acos -t aros -t aros_acos -t users -t groups -t referents -t regroupementszonesgeo -t servicesinstructeurs -t structuresreferentes -t structuresreferentes_zonesgeographiques -t typesorients -t typoscontrats -t users_zonesgeographiques -t zonesgeographiques -t zonesgeographiques_regroupementszonesgeo"

TABLES_APPLICATIVES="accoemploi -t actions -t typesactions -t difdisps -t difsocs -t diflogs -t nataccosocfams -t nataccosocindis -t natmobs -t nivetus"

# on dump seulement les tables applicatives ( Données seulement )
function tablesapplicatives(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -a -d -t $TABLES_APPLICATIVES webrsa > $APP_DIR/webrsa_appli.sql
}
# on dump seulement les tables applicatives ( Structures seulement )
function tablesapplicatives_struct(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -s -d -t $TABLES_APPLICATIVES webrsa > $APP_DIR/webrsa_appli_struct.sql
}

# on dump seulement les tables métiers ( Données seulement )
function tablesmetiers(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -a -d -t $TABLES_METIERS webrsa > $APP_DIR/webrsa_metiers.sql
}
# on dump seulement les tables métiers ( Structures seulement )
function tablesmetiers_struct(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -s -d -t $TABLES_METIERS webrsa > $APP_DIR/webrsa_metiers_struct.sql
}
# on dump toute la base
function alldatabase(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -d webrsa > $APP_DIR/webrsaBD.sql
}

# on dump toute la structure de la base
function allBD_struct(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -s -d webrsa > $APP_DIR/webrsa_struct.sql
}

# on dump toute les données de la base
function allBD_data(){
    pg_dump -E UTF-8 -h localhost -p 5432 -U webrsa -a -d webrsa > $APP_DIR/webrsa_data.sql
}

# # on restaure la partie de la base que nous avons dumpé précédemment 
# function restore(){ # FIXME Problème au niveau de la restauration
#         pg_restore -h localhost -p 5432 -U webrsa -d webrsa_test $APP_DIR/webrsa_$2_struct.sql
# }


case $1 in
    alldatabase)
        alldatabase
    ;;
    tablesapplicatives)
        tablesapplicatives
    ;;
    tablesapplicatives_struct)
        tablesapplicatives_struct
    ;;
    tablesmetiers)
        tablesmetiers
    ;;
    tablesmetiers_struct)
        tablesmetiers_struct
    ;;
    allBD_struct)
        allBD_struct
    ;;
    allBD_data)
        allBD_data
    ;;
#     restore)
#         restore $2 # FIXME : Problèmes d'arguments
#     ;;
    *)
        echo "Usage: $ME {alldatabase | tablesmetiers | tablesmetiers_struct | tablesapplicatives | tablesapplicatives_struct | restore}"
        exit 1
    ;;
esac

# case $2 in
#     metiers)
#         metiers
#     ;;
#     applicatives)
#         applicatives
#     ;;
#     *)
#         echo "Usage: $ME $1 {metiers | applicatives}"
#         exit 1
#     ;;
# esac