<?php 
    class Comiteapre extends AppModel
    {
        var $name = 'Comiteapre';
        var $actsAs = array( 'Enumerable' );

        var $hasAndBelongsToMany = array(
            'Participantcomite' => array(
                'className'              => 'Participantcomite',
                'joinTable'              => 'comitesapres_participantscomites',
                'foreignKey'             => 'comiteapre_id',
                'associationForeignKey'  => 'participantcomite_id'
            ),
            'Apre' => array(
                'className'              => 'Apre',
                'joinTable'              => 'apres_comitesapres',
                'foreignKey'             => 'comiteapre_id',
                'associationForeignKey'  => 'apre_id'
            )
        );

        var $validate = array(
            'datecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'heurecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'lieucomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'intitulecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'observationcomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

        function search( $criterescomite ) {
            /// Conditions de base
            $conditions = array(
            );

            /// Critères
            $conditions[] = 'Comiteapre.id IS NOT NULL';

            /// Critères sur le Comité - date du comité
            if( isset( $criterescomite['Comiteapre']['datecomite'] ) && !empty( $criterescomite['Comiteapre']['datecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Comiteapre']['datecomite_from']['year'] ) && valid_int( $criterescomite['Comiteapre']['datecomite_from']['month'] ) && valid_int( $criterescomite['Comiteapre']['datecomite_from']['day'] ) );
                $valid_to = ( valid_int( $criterescomite['Comiteapre']['datecomite_to']['year'] ) && valid_int( $criterescomite['Comiteapre']['datecomite_to']['month'] ) && valid_int( $criterescomite['Comiteapre']['datecomite_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.datecomite BETWEEN \''.implode( '-', array( $criterescomite['Comiteapre']['datecomite_from']['year'], $criterescomite['Comiteapre']['datecomite_from']['month'], $criterescomite['Comiteapre']['datecomite_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescomite['Comiteapre']['datecomite_to']['year'], $criterescomite['Comiteapre']['datecomite_to']['month'], $criterescomite['Comiteapre']['datecomite_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur le Comité - heure du comité
            if( isset( $criterescomite['Comiteapre']['heurecomite'] ) && !empty( $criterescomite['Comiteapre']['heurecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Comiteapre']['heurecomite_from']['hour'] ) && valid_int( $criterescomite['Comiteapre']['heurecomite_from']['min'] ) );
                $valid_to = ( valid_int( $criterescomite['Comiteapre']['heurecomite_to']['hour'] ) && valid_int( $criterescomite['Comiteapre']['heurecomite_to']['min'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.heurecomite BETWEEN \''.implode( ':', array( $criterescomite['Comiteapre']['heurecomite_from']['hour'], $criterescomite['Comiteapre']['heurecomite_from']['min'] ) ).'\' AND \''.implode( ':', array( $criterescomite['Comiteapre']['heurecomite_to']['hour'], $criterescomite['Comiteapre']['heurecomite_to']['min'] ) ).'\'';
                }
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Comiteapre"."id"',
                    '"Comiteapre"."datecomite"',
                    '"Comiteapre"."heurecomite"',
                    '"Comiteapre"."lieucomite"',
                    '"Comiteapre"."intitulecomite"',
                    '"Comiteapre"."observationcomite"'
                ),
                'recursive' => -1,
                'order' => array( '"Comiteapre"."datecomite" ASC' ),
                'conditions' => $conditions
            );

            return $query;
        }


    }
?>