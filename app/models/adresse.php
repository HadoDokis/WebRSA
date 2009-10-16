<?php
    class Adresse extends AppModel
    {
        var $name = 'Adresse';
        var $useTable = 'adresses';

        //*********************************************************************

        /**
            Associations
        */
        var $hasOne = array(
            'Adressefoyer' => array(
                'className'     => 'Adressefoyer',
                'foreignKey'    => 'adresse_id'
            )
        );

        //*********************************************************************

        /**
            Validation ... TODO
        */
        var $validate = array(
//             'numvoie' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'typevoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nomvoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            // FIXME: validation format code
            'numcomptt' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => array( 'between', 5, 5 ),
                    'message' => 'Le code INSEE se compose de 5 caractères'
                )
            ),
            'codepos' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'locaadr' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'pays' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
        );


        /**
        *   FIXME: on retourne des locaadr sur lesquels on n'a pas le contrôle
        *       => à mettre en table paramétrages
        */

        function listeCodesInseeLocalites( $codesFiltres = array() ){
            $conditions = array();

            if( !empty( $codesFiltres ) ) {
                $conditions['Adresse.numcomptt'] = $codesFiltres;
            }

            $codes = $this->find(
                'all',
                array(
                    'fields' => array( 'DISTINCT Adresse.numcomptt', 'Adresse.locaadr' ),
                    'conditions' => $conditions,
                    'recursive' => -1,
                    'order' => 'Adresse.numcomptt'
                )
            );
            $ids = Set::extract( $codes, '/Adresse/numcomptt' );
            $values = Set::format( $codes, '{0} {1}', array( '{n}.Adresse.numcomptt', '{n}.Adresse.locaadr' ) );
            return array_combine( $ids, $values );
        }
    }
?>