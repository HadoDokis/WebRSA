<?php
    class Cohortecomiteapre extends AppModel
    {
        var $name = 'Cohortescomitesapres';
        var $useTable = false;
        var $actsAs = array( 'Enumerable' );

        function search( $avisComite, $criterescomite ) {
            /// Conditions de base
            $conditions = array(
            );

            if( !empty( $avisComite ) ) {
                if( $avisComite == 'Cohortecomiteapre::aviscomite' ) {
//                     $conditions[] = 'Apre.id NOT IN ApreComiteapre.apre_id';
                }
                else if( $avisComite == 'Cohortecomiteapre::notificationscomite' ) {
//                     $conditions[] = 'Apre.etatdossierapre = \'INC\'';
                }
            }

            /// Critères sur le Comité - intitulé du comité
            if( isset( $criterescomite['Cohortecomiteapre']['id'] ) && !empty( $criterescomite['Cohortecomiteapre']['id'] ) ) {
                $conditions['Comiteapre.id'] = $criterescomite['Cohortecomiteapre']['id'];
            }

            /// Critères sur le Comité - date du comité
            if( isset( $criterescomite['Cohortecomiteapre']['datecomite'] ) && !empty( $criterescomite['Cohortecomiteapre']['datecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['year'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['month'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['day'] ) );
                $valid_to = ( valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['year'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['month'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.datecomite BETWEEN \''.implode( '-', array( $criterescomite['Cohortecomiteapre']['datecomite_from']['year'], $criterescomite['Cohortecomiteapre']['datecomite_from']['month'], $criterescomite['Cohortecomiteapre']['datecomite_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescomite['Cohortecomiteapre']['datecomite_to']['year'], $criterescomite['Cohortecomiteapre']['datecomite_to']['month'], $criterescomite['Cohortecomiteapre']['datecomite_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur le Comité - heure du comité
            if( isset( $criterescomite['Cohortecomiteapre']['heurecomite'] ) && !empty( $criterescomite['Cohortecomiteapre']['heurecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_from']['hour'] ) && valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_from']['min'] ) );
                $valid_to = ( valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_to']['hour'] ) && valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_to']['min'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.heurecomite BETWEEN \''.implode( ':', array( $criterescomite['Cohortecomiteapre']['heurecomite_from']['hour'], $criterescomite['Cohortecomiteapre']['heurecomite_from']['min'] ) ).'\' AND \''.implode( ':', array( $criterescomite['Cohortecomiteapre']['heurecomite_to']['hour'], $criterescomite['Cohortecomiteapre']['heurecomite_to']['min'] ) ).'\'';
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
                    '"ApreComiteapre"."apre_id"',
                    '"ApreComiteapre"."comiteapre_id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."qual"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Apre"."id"',
                    '"Apre"."datedemandeapre"',
                    '"Apre"."statutapre"',
//                     '"Apre"."comite_id"',
//                     '"Apre"."montantdemande"',
//                     '"Apre"."montantattribue"',
                ),
                'recursive' => -1,
                'joins' => array(
//                     array(
//                         'table'      => 'comitesapres',
//                         'alias'      => 'Comiteapre',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'ApreComiteapre.comiteapre_id = Comiteapre.id' )
//                     ),
                    array(
                        'table'      => 'apres_comitesapres',
                        'alias'      => 'ApreComiteapre',
                        'type'       => 'LEFT OUTER',
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
                ),
                'order' => array( '"Comiteapre"."datecomite" ASC' ),
                'conditions' => $conditions
            );

            return $query;
        }


    }
?>