<?php 
    class Apre extends AppModel
    {
        var $name = 'Apre';
        var $actsAs = array( 'Enumerable' );

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
            'Comiteexamenapre' => array(
                'className'              => 'Comiteexamenapre',
                'joinTable'              => 'apres_comitesexamenapres',
                'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'comiteexamenapre_id'
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

        function afterFind( $results, $primary = false ) {
            parent::afterFind( $results, $primary );
//     debug($results);
            if( !empty( $results ) ) {
                $isArray = true;
                if( isset( $results['id'] ) ) {
                    $results = array( 'Apre' => array( $results ) );
                    $isArray = false;
                }
                foreach( $results as $key => $result ) {
                    $results[$key]['Natureaide'] = array();

                    /// Essaie de récupération des pièces des aides liées

                    foreach( $this->aidesApre as $model ) {
                        $results[$key]['Natureaide'][$model] = $this->{$model}->find(
                            'count',
                            array(
                                'conditions' => array(
                                    "$model.apre_id" => Set::classicExtract( $result, 'Apre.id' )
                                )
                            )
                        );
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
    }
?>