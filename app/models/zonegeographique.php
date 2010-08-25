<?php
    class Zonegeographique extends AppModel
    {
        var $name = 'Zonegeographique';
        var $useTable = 'zonesgeographiques';
        var $displayField = 'libelle';

        //---------------------------------------------------------------------

        var $hasAndBelongsToMany = array(
            'User' => array(
                'classname' => 'User',
                'joinTable' => 'users_zonesgeographiques',
                'foreignKey' => 'zonegeographique_id',
                'associationForeignKey' => 'user_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'joinTable' => 'structuresreferentes_zonesgeographiques',
                'foreignKey' => 'zonegeographique_id',
                'associationForeignKey' => 'structurereferente_id'
            )
        );

        var $validate = array(
            'libelle' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'Valeur déjà présente'
                )
            ),
            'codeinsee' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'Valeur déjà présente'
                )
            )
        );

        /**
        *
        *
        */

        function listeCodesInseeLocalites( $codesFiltres = array(), $filtre_zone_geo = true ){
            $conditions = array();

            if( $filtre_zone_geo == true ) {
                if( !empty( $codesFiltres ) ) {
                    $conditions['Zonegeographique.codeinsee'] = $codesFiltres;
                }
                else {
                    $conditions['Zonegeographique.codeinsee'] = null;
                }
            }

            $codes = $this->find(
                'all',
                array(
                    'fields' => array( 'DISTINCT Zonegeographique.codeinsee', 'Zonegeographique.libelle' ),
                    'conditions' => $conditions,
                    'recursive' => -1,
                    'order' => 'Zonegeographique.codeinsee'
                )
            );

            if( !empty( $codes ) ) {
                $ids = Set::extract( $codes, '/Zonegeographique/codeinsee' );
                $values = Set::format( $codes, '{0} {1}', array( '{n}.Zonegeographique.codeinsee', '{n}.Zonegeographique.libelle' ) );
                return array_combine( $ids, $values );
            }
            else {
                return $codes;
            }
        }
    }

?>
