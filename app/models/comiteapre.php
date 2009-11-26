<?php
    class Comiteapre extends AppModel
    {
        var $name = 'Comiteapre';
        var $actsAs = array( 'Enumerable' );
        var $displayField = 'intitulecomite';
        //var $order = array( '' ); // <-- TODO

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
                    '"Comiteapre"."observationcomite"',
//                     '"Dossier"."numdemrsa"',
//                     '"Dossier"."matricule"',
//                     '"Personne"."qual"',
//                     '"Personne"."nom"',
//                     '"Personne"."prenom"',
//                     '"Personne"."dtnai"',
//                     '"Personne"."nir"',
//                     '"Adresse"."locaadr"',
//                     '"Adresse"."codepos"',
//                     '"Apre"."datedemandeapre"',
                ),
                'recursive' => -1,
                /*'joins' => array(
                    array(
                        'table'      => 'apres_comitesapres',
                        'alias'      => 'ApreComiteapre',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'ApreComiteapre.comiteapre_id = Comiteapre.id' )
                    ),
                    array(
                        'table'      => 'apres',
                        'alias'      => 'Apre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
                    ),
//                     array(
//                         'table'      => 'relancesapres',
//                         'alias'      => 'Relanceapre',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Relanceapre.apre_id = Apre.id' )
//                     ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Apre.personne_id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                ),*/
                'order' => array( '"Comiteapre"."datecomite" ASC' ),
                'conditions' => $conditions
            );

            return $query;
        }


    }
?>