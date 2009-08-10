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


        function search( $criteres ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $mois = Set::extract( $criteres, 'Filtre.dtref' );

            /// Date du flux financier
            if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.dtref' ) ) {
                $mois = $mois['month'];
                $conditions[] = 'EXTRACT(MONTH FROM Identificationflux.dtref) = '.$mois;
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Totalisationacompte"."type_totalisation"',
                    'SUM("Totalisationacompte"."mttotsoclrsa") AS "Totalisationacompte__mttotsoclrsa"',
                    'SUM("Totalisationacompte"."mttotsoclmajorsa") AS "Totalisationacompte__mttotsoclmajorsa"',
                    'SUM("Totalisationacompte"."mttotlocalrsa") AS "Totalisationacompte__mttotlocalrsa"',
                    'SUM("Totalisationacompte"."mttotrsa") AS "Totalisationacompte__mttotrsa"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'identificationsflux',
                        'alias'      => 'Identificationflux',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Totalisationacompte.identificationflux_id = Identificationflux.id' ),
                    )
                ),
                'group' => array(
                    'Totalisationacompte.type_totalisation'
                ),
                'conditions' => $conditions
            );
// debug( $query );
            return $query;

        }
    }

?>