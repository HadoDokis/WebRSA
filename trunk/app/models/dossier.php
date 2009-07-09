<?php
    class Dossier extends AppModel
    {
        var $name = 'Dossier';
        var $useTable = 'dossiers_rsa';

        //*********************************************************************

        var $hasOne = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'dossier_rsa_id'
            ),
            'Situationdossierrsa' => array(
                'classname' => 'Situationdossierrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Avispcgdroitrsa' => array(
                'classname' => 'Avispcgdroitrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Detaildroitrsa' => array(
                'classname' => 'Detaildroitrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Suiviinstruction' => array( // FIXME: hasMany
                'classname' => 'Suiviinstruction',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Infofinanciere' => array( // FIXME: hasMany
                'classname' => 'Infofinanciere',
                'foreignKey' => 'dossier_rsa_id'
            )
        );



        //*********************************************************************

        var $validate = array(
            'numdemrsa' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Cette valeur est déjà utilisée'
                ),
                array(
                    'rule' => 'alphaNumeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
                array(
                    'rule' => array( 'between', 11, 11 ),
                    'message' => 'Le n° de demande est composé de 11 caractères'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtdemrsa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'matricule' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce numéro CAF est déjà utilisé'
                ),
                array(
                    'rule' => array( 'between', 15, 15 ),
                    'message' => 'Le numéro CAF est composé de 15 chiffres'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
                // TODO: format NIR
            ),
        );

        //*********************************************************************

        function beforeSave() {
            // Champs déduits
            if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
                $this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
            }

            return parent::beforeSave();
        }

        //*********************************************************************

        function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) {
            $this->Foyer->unbindModelAll();

            $this->Foyer->bindModel(
                array(
                    'hasOne'=>array(
                        'Adressefoyer' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."foyer_id" = "Foyer"."id"',
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

            if( $filtre_zone_geo ) {
                $params = array (
                    'conditions' => array(
                        'Adresse.numcomptt' => array_values( $zonesGeographiques )
                    )
                );
            }
            else {
                $params = array();
            }

            $foyers = $this->Foyer->find( 'all', $params );

            $return = Set::extract( $foyers, '{n}.Foyer.dossier_rsa_id' );
            return ( !empty( $return ) ? $return : null );
        }
    }
?>