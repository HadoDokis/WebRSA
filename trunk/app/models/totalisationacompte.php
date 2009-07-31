<?php 
    class Totalisationacompte extends AppModel
    {
        var $name = 'Totalisationacompte';
        var $useTable = 'totalisationsacomptes';


        var $belongsTo = array(
            'Identificationflux' => array(
                'classname' => 'Identificationflux',
                'foreignKey' => 'identificationflux_id'
            )
        );

        var $validate = array(
            'type_totalisation' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclmajorsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotlocalrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );


        function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $mois = Set::extract( $criteres, 'Filtre.dtref' );

            // ...
            if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.dtref' ) ) {
                $mois = $mois['month'];
                $conditions[] = 'EXTRACT(MONTH FROM Identificationflux.dtref) = '.$mois;
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Totalisationacompte"."id"',
                    '"Totalisationacompte"."identificationflux_id"',
                    '"Totalisationacompte"."type_totalisation"',
                    '"Totalisationacompte"."mttotsoclrsa"',
                    '"Totalisationacompte"."mttotsoclmajorsa"',
                    '"Totalisationacompte"."mttotlocalrsa"',
                    '"Totalisationacompte"."mttotrsa"',
                    '"Identificationflux"."id"',
                    '"Identificationflux"."dtref"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'identificationsflux',
                        'alias'      => 'Identificationflux',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Totalisationacompte.identificationflux_id = Identificationflux.id' )
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;

        }
    }

?>