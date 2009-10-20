<?php
    class Dossierpdo extends AppModel
    {
        var $name = 'Dossierpdo';
        var $useTable = false;

        var $_types = array(
            'etat' => array(
                'fields' => array(
                    '"Dossier"."id"',
                    '"Situationdossierrsa"."etatdosrsa"',
                    '"Situationdossierrsa"."dtrefursa"',
                    '"Situationdossierrsa"."dtclorsa"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
                    )
                )
            ),
            'propopdo' => array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."dossier_rsa_id"',
                    '"Propopdo"."typepdo_id"',
                    '"Propopdo"."decisionpdo_id"',
                    '"Propopdo"."typenotif_id"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."motifpdo"',
                    '"Propopdo"."datenotif"',
                    '"Propopdo"."commentairepdo"',
//                     '"Decisionpdo"."id"',
                    '"Decisionpdo"."libelle"',
//                     '"Typenotif"."id"',
                    '"Typenotif"."libelle"',
//                     '"Typepdo"."id"',
                    '"Typepdo"."libelle"',
                    '"Personne"."id"',
                    '"Personne"."pieecpres"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'typesnotifs',
                        'alias'      => 'Typenotif',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typenotif_id = Typenotif.id' )
                    ),
                    array(
                        'table'      => 'decisionspdos',
                        'alias'      => 'Decisionpdo',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.decisionpdo_id = Decisionpdo.id' )
                    ),
                    array(
                        'table'      => 'typespdos',
                        'alias'      => 'Typepdo',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typepdo_id = Typepdo.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.rolepers = \'DEM\'',
                            'Prestation.natprest = \'RSA\''
                        )
                    )
                ),
                'order' => 'Propopdo.datedecisionpdo ASC',
            )
        );

        function prepare( $type, $params = array() ) {
            $types = array_keys( $this->_types );
            if( !in_array( $type, $types ) ) {
                trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
            }
            else {
                $query = $this->_types[$type];

                switch( $type ) {
                    case 'etat':
                        $query = Set::merge( $query, $params );
                        break;
                    case 'propopdo':
                        $query = Set::merge( $query, $params );
                        break;
                }

                return $query;
            }
        }

    }
?>
