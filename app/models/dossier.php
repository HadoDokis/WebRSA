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
            'Suiviinstruction' => array(
                'classname' => 'Suiviinstruction',
                'foreignKey' => 'dossier_rsa_id'
            ),
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
            )
        );

        //*********************************************************************

        function beforeSave() {
            // Champs déduits
            if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
                $this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
            }

            return parent::beforeSave();
        }
    }
?>