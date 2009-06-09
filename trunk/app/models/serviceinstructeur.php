<?php
    class Serviceinstructeur extends AppModel
    {
        var $name = 'Serviceinstructeur';
        var $useTable = 'servicesinstructeurs';

        function listOptions() {
            return  $this->find(
                'list', 
                array (
                    'fields' => array(
                        'id',
                        'lib_service'
                    ),
                    'order'  => array( 'lib_service ASC' )
                )
            );
        }

        var $validate = array(
            'numdepins' => array(
                array(
                    'rule' => 'alphaNumeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
                array(
                    'rule' => array( 'between', 3, 3 ),
                    'message' => 'Le n° de département est composé de 3 caractères'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeserins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numcomins' => array(
                array(
                    'rule' => 'alphaNumeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
                array(
                    'rule' => array( 'between', 3, 3 ),
                    'message' => 'Le n° de commune est composé de 3 caractères'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
//             'numagrins'
        );
    }

?>
