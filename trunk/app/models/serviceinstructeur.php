<?php
    class Serviceinstructeur extends AppModel
    {
        var $name = 'Serviceinstructeur';
        var $useTable = 'servicesinstructeurs';
        var $displayField = 'lib_service';
        var $order = 'Serviceinstructeur.lib_service ASC';

        function listOptions() {
            return  $this->find(
                'list',
                array (
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                    'order'  => array( 'Serviceinstructeur.lib_service ASC' )
                )
            );
        }


        var $validate = array(
            'lib_service' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'code_insee' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                    // FIXME: format
                )
            ),
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
            'numagrins' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Cette valeur est déjà utilisée'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
                array(
                    'rule' => array( 'between', 1, 2 ),
                    'message' => 'Le n° d\'agrément est composé de 2 caractères'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

        var $_types = array(
            'list' => array(
                'fields' => array(
                    '"Serviceinstructeur"."id"',
                    '"Serviceinstructeur"."lib_service"',
                    '"Serviceinstructeur"."num_rue"',
                    '"Serviceinstructeur"."nom_rue"',
                    '"Serviceinstructeur"."complement_adr"',
                    '"Serviceinstructeur"."code_insee"',
                    '"Serviceinstructeur"."code_postal"',
                    '"Serviceinstructeur"."ville"',
                    '"Serviceinstructeur"."numdepins"',
                    '"Serviceinstructeur"."typeserins"',
                    '"Serviceinstructeur"."numcomins"',
                    '"Serviceinstructeur"."numagrins"',
                    '"Serviceinstructeur"."type_voie"',
                    'COUNT("User"."id") AS "Serviceinstructeur__nbUsers"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'users',
                        'alias'      => 'User',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Serviceinstructeur.id = User.serviceinstructeur_id' )
                    ),
                ),
                'group' => array(
                    '"Serviceinstructeur"."id"',
                    '"Serviceinstructeur"."lib_service"',
                    '"Serviceinstructeur"."num_rue"',
                    '"Serviceinstructeur"."nom_rue"',
                    '"Serviceinstructeur"."complement_adr"',
                    '"Serviceinstructeur"."code_insee"',
                    '"Serviceinstructeur"."code_postal"',
                    '"Serviceinstructeur"."ville"',
                    '"Serviceinstructeur"."numdepins"',
                    '"Serviceinstructeur"."typeserins"',
                    '"Serviceinstructeur"."numcomins"',
                    '"Serviceinstructeur"."numagrins"',
                    '"Serviceinstructeur"."type_voie"',
                ),
                'order' => 'Serviceinstructeur.lib_service ASC',
            )
        );

        function prepare( $type, $params = array() ) {
            $types = array_keys( $this->_types );
            if( !in_array( $type, $types ) ) {
                trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
            }
            else {
                $querydata = $this->_types[$type];
                $querydata = Set::merge( $querydata, $params );

                return $querydata;
            }
        }
    }
?>
