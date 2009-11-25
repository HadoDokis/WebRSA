<?php 
    class Apre extends AppModel
    {
        var $name = 'Apre';
        var $actsAs = array( 'Enumerable' );
        var $displayField = 'numeroapre';

        var $validate = array(
            'typedemandeapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'activitebeneficiaire' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'referentapre_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );

        var $enumFields = array(
            'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
            'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
            'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
            'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
            'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
            'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
            'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
        );


        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Referentapre'
        );

        var $hasOne = array(
            'Formqualif',
            'Actprof',
            'Permisb',
            'Amenaglogt',
            'Acccreaentr',
            'Acqmatprof',
            'Locvehicinsert'
        );

        var $aidesApre = array( 'Formqualif', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' );

        var $hasMany = array(
//             'Formqualif' => array(
//                 'classname' => 'Formqualif',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Actprof' => array(
//                 'classname' => 'Actprof',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Permisb' => array(
//                 'classname' => 'Permisb',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Amenaglogt' => array(
//                 'classname' => 'Amenaglogt',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Acccreaentr' => array(
//                 'classname' => 'Acccreaentr',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Acqmatprof' => array(
//                 'classname' => 'Acqmatprof',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Locvehicinsert' => array(
//                 'classname' => 'Locvehicinsert',
//                 'foreignKey' => 'apre_id',
//             ),
            'Montantconsomme' => array(
                'classname' => 'Montantconsomme',
                'foreignKey' => 'apre_id',
            ),
            'Relanceapre' => array(
                'classname' => 'Relanceapre',
                'foreignKey' => 'apre_id',
            )
        );

        var $hasAndBelongsToMany = array(
            'Pieceapre' => array(
                 'className'              => 'Pieceapre',
                 'joinTable'              => 'apres_piecesapre',
                 'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'pieceapre_id'
            ),
            'Comiteapre' => array(
                'className'              => 'Comiteapre',
                'joinTable'              => 'apres_comitesapres',
                'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'comiteapre_id'
            )
        );


        function dossierId( $apre_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Apre.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $apre = $this->findById( $apre_id, null, null, 0 );

            if( !empty( $apre ) ) {
                return $apre['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        /**
        *
        */

        function _nbrNormalPieces() {
            $nbNormalPieces = array();
            $nbNormalPieces['Apre'] = $this->Pieceapre->find( 'count' );
            foreach( $this->aidesApre as $model ) {
                $nbNormalPieces[$model] = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'count' );
            }
            return $nbNormalPieces;
        }

        /**
        *
        */

        function afterFind( $results, $primary = false ) {
            parent::afterFind( $results, $primary );
//     debug($results);
            if( !empty( $results ) ) {

                $isArray = true;
                if( isset( $results['id'] ) ) {
                    $results = array( 'Apre' => array( $results ) );
                    $isArray = false;
                }

                // Nombre de pièces prévues pour l'APRE et chaque type d'aide
                $nbNormalPieces = $this->_nbrNormalPieces();

                foreach( $results as $key => $result ) {
                    if( !empty( $results[$key]['Apre'] ) ) {
                        $results[$key]['Natureaide'] = array();
                        $results[$key]['Piecemanquante'] = array();

                        // Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
                        $nbPiecesapre = $this->AprePieceapre->find( 'count', array( 'conditions' => array( 'apre_id' => Set::classicExtract( $result, 'Apre.id' ) ) ) );
                        $results[$key]['Piecemanquante']['Apre'] = abs( $nbPiecesapre - $nbNormalPieces['Apre'] );

                        /// Essaie de récupération des pièces des aides liées
                        foreach( $this->aidesApre as $model ) {
                            // Nombre de pièces trouvées par-rapport au nombre de pièces prévues pour chaque type d'aide
                            $aides = $this->{$model}->find(
                                'all',
                                array(
                                    'conditions' => array(
                                        "$model.apre_id" => Set::classicExtract( $result, 'Apre.id' )
                                    )
                                )
                            );

                            // Combien d'aides liées à l'APRE sont présentes pour chaque type d'aide
                            $results[$key]['Natureaide'][$model] = count( $aides );

                            if( !empty( $aides ) ) {
                                $results[$key]['Piecemanquante'][$model] = abs( $nbNormalPieces[$model] - count( Set::extract( $aides, '/Piece'.strtolower( $model ) ) ) );
                            }
                        }

                        $results[$key]['Apre']['etatdossierapre'] = ( ( array_sum( $results[$key]['Piecemanquante'] ) == 0 ) ? 'COM' : 'INC' );
                    }
                }

                if( !$isArray ) {
                    $results = $results['Apre'][0];
                }
            }

            return $results;
        }

        /**
        *
        */

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

            // FIXME: à mettre dans le behavior
            foreach( array_keys( $this->enumFields ) as $enum ) {
                if( empty( $this->data[$this->name][$enum] ) ) {
                    $this->data[$this->name][$enum] = null;
                }
            }

            return $return;
        }

        /**
        *
        */

        function afterSave( $created ) {
            $return = parent::afterSave( $created );

            $nbNormalPieces = $this->_nbrNormalPieces();
            $piecesmanquantes = array();

            // Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
            $nbPiecesapre = $this->AprePieceapre->find( 'count', array( 'conditions' => array( 'apre_id' => $this->id ) ) );
            $piecesmanquantes['Apre'] = abs( $nbPiecesapre - $nbNormalPieces['Apre'] );

            /// Essaie de récupération des pièces des aides liées
            foreach( $this->aidesApre as $model ) {
                // Nombre de pièces trouvées par-rapport au nombre de pièces prévues pour chaque type d'aide
                $aides = $this->{$model}->find(
                    'all',
                    array(
                        'conditions' => array(
                            "$model.apre_id" => $this->id
                        )
                    )
                );

                if( !empty( $aides ) ) {
                    $piecesmanquantes[$model] = abs( $nbNormalPieces[$model] - count( Set::extract( $aides, '/Piece'.strtolower( $model ) ) ) );
                }
            }

            $dossiercomplet = ( ( array_sum( $piecesmanquantes ) == 0 ) ? 'COM' : 'INC' );

            $this->query( "UPDATE apres SET etatdossierapre = '$dossiercomplet' WHERE id = {$this->id};" );
//             debug( "UPDATE apres SET etatdossierapre = '$dossiercomplet' WHERE id = {$this->id};" );

            return $return;
        }
    }
?>