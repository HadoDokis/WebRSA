<?php
    class Personne extends AppModel
    {
        var $name = 'Personne';
        var $useTable = 'personnes';

        //---------------------------------------------------------------------

        var $hasOne = array(
            'TitreSejour',
            'Dspp',
            'Dossiercaf',
            'Avispcgpersonne',
            'Prestation' => array(
                'foreignKey' => 'personne_id',
                'conditions' => array (
                    'Prestation.natprest' => array( 'RSA' )
//                     'Prestation.natprest' => array( 'RSA', 'PFA' )
                )
            )
        );

        var $belongsTo = array(
            'Foyer'
        );

        var $hasMany = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
                'foreignKey' => 'personne_id',
            ),
            'Orientstruct' => array(
                'classname' => 'Orientstruct',
                'foreignKey' => 'personne_id'
            ),
            'Rendezvous' => array(
                'classname' => 'Rendezvous',
                'foreignKey' => 'personne_id'
            ),
            'Activite' => array(
                'classname' => 'Activite',
                'foreignKey' => 'personne_id',
            ),
        );

        //---------------------------------------------------------------------

        var $validate = array(
            // Qualité
            'qual' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nom' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'prenom' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
//             'nir' => array(
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce NIR est déjà utilisé'
//                 ),
//                 array(
//                     'rule' => array( 'between', 15, 15 ),
//                     'message' => 'Le NIR est composé de 15 chiffres'
//                 ),
//                 array(
//                     'rule' => 'numeric',
//                     'message' => 'Veuillez entrer une valeur numérique.',
//                     'allowEmpty' => true
//                 ),
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//                 // TODO: format NIR
//             ),
            'dtnai' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'rgnai' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => array('comparison', '>', 0 ),
                    'message' => 'Veuillez entrer un nombre positif.'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            //
            'nati' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
//             'dtnati' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
            'pieecpres' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

        //*********************************************************************

        function beforeSave( $options = array() ) {
            parent::beforeSave( $options );

            // Champs déduits
            if( !empty( $this->data['Personne']['qual'] ) ) {
                $this->data['Personne']['sexe'] = ( $this->data['Personne']['qual'] == 'MR' ) ? 1 : 2;
            }

            return true;
        }

        //*********************************************************************

//         function afterSave( $created ) {
//             $return = parent::afterSave( $created );
//
//             $thisPersonne = $this->findById( $this->data['Personne']['id'], null, null, -1 );
//             $this->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );
//
//             return $return;
//         }

        //*********************************************************************

        function dossierId( $personne_id ) {
            $this->unbindModelAll();
            $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
            $personne = $this->findById( $personne_id, null, null, 0 );
            if( !empty( $personne ) ) {
                return $personne['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

        function soumisDroitsEtDevoirs( $personne_id ) {
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasMany' => array(
                        'Ressource' => array(
                            'order' => array( 'dfress DESC' )
                        )
                    ),
                    'hasOne' => array(
                        'Dspp',
                        'Prestation' => array(
                            'foreignKey' => 'personne_id',
                            'conditions' => array (
//                                 'Prestation.natprest' => array( 'RSA', 'PFA' )
                                'Prestation.natprest' => array( 'RSA' )
                            )
                        )
                    )
                )
            );

            $personne = $this->findById( $personne_id, null, null, 1 );
            if( isset( $personne['Prestation'] ) && ( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ) ) {
                if( isset( $personne['Ressource'] ) && isset( $personne['Ressource'][0] ) && isset( $personne['Ressource'][0]['mtpersressmenrsa'] ) ) {
                    $montant = $personne['Ressource'][0]['mtpersressmenrsa'];
                }
                else {
                    $montant = 0;
                }

                if( $montant < 500 ) {
                    return true;
                }
                else {
                    $montantForfaitaire = $this->Foyer->montantForfaitaire( $personne['Personne']['foyer_id'] );
                    if( $montantForfaitaire ) {
                        return $montantForfaitaire;
                    }
                }

                $dspp = array_filter( array( 'Dspp' => $personne['Dspp'] ) );
                $hispro = Set::extract( $dspp, 'Dspp.hispro' );
                if( $hispro !== NULL ) {
                    // Passé professionnel ? -> Emploi
                    //     1901 : Vous avez toujours travaillé
                    //     1902 : Vous travaillez par intermittence
                    if( $dspp['Dspp']['hispro'] == '1901' || $dspp['Dspp']['hispro'] == '1902' ) {
                        return false;
                    }
                    else {
                        return true;
                    }
                }
            }
            return false;
        }

        //*********************************************************************

        function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) { // TODO
            $this->unbindModelAll();

            $this->bindModel(
                array(
                    'hasOne'=>array(
                        'Adressefoyer' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."foyer_id" = "Personne"."foyer_id"',
                                '"Adressefoyer"."rgadr" = \'01\''
                            )
                        ),
                        'Adresse' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."adresse_id" = "Adresse"."id"'
                            )
                        )
                    )
                )
            );

            $conditions = array();
            if( $filtre_zone_geo ) {
                $conditions = array( 'Adresse.numcomptt' => ( !empty( $zonesGeographiques ) ? array_values( $zonesGeographiques ) : array() ) );
            }

            $personnes = $this->find( 'all', array ( 'conditions' => $conditions ) );

            $return = Set::extract( $personnes, '{n}.Personne.id' );
            return ( !empty( $return ) ? $return : null );
        }
    }
?>