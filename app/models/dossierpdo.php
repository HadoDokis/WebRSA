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
            'derogation' => array(
                'fields' => array(
                    '"Derogation"."id"',
                    '"Derogation"."typdero"',
                    '"Derogation"."avisdero"',
                    '"Derogation"."ddavisdero"',
                    '"Derogation"."dfavisdero"',
                    '"Personne"."id"',
                    '"Personne"."pieecpres"',
                    'Avispcgpersonne.personne_id',
                    'Avispcgpersonne.id',
                    'Derogation.avispcgpersonne_id'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'avispcgpersonnes',
                        'alias'      => 'Avispcgpersonne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Derogation.avispcgpersonne_id = Avispcgpersonne.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Avispcgpersonne.personne_id = Personne.id' )
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
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    )
                ),
                'order' => 'Derogation.ddavisdero ASC',
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
                    case 'derogation':
                        $query = Set::merge( $query, $params );
                        break;
                }

                return $query;
            }
        }

    }
?>
