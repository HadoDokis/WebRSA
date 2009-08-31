<?php 
    class Suiviinsertion extends AppModel
    {
        var $name = 'Suiviinsertion';


        function search( $criteres ) {
//             /// Conditions de base
//             $conditions = array();
// 
//             /// Critères
//             $mois = Set::extract( $criteres, 'Filtre.dtcreaflux' );
// 
//             /// Date du flux financier
//             if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.dtcreaflux' ) ) {
//                 $mois = $mois['month'];
//                 $conditions[] = 'EXTRACT(MONTH FROM Identificationflux.dtcreaflux) = '.$mois;
//             }
// 
//             /// Requête
//             $this->Dossier =& ClassRegistry::init( 'Dossier' );
// 
//             $query = array(
//                 'fields' => array(
//                     '"Totalisationacompte"."type_totalisation"',
//                     'SUM("Totalisationacompte"."mttotsoclrsa") AS "Totalisationacompte__mttotsoclrsa"',
//                     'SUM("Totalisationacompte"."mttotsoclmajorsa") AS "Totalisationacompte__mttotsoclmajorsa"',
//                     'SUM("Totalisationacompte"."mttotlocalrsa") AS "Totalisationacompte__mttotlocalrsa"',
//                     'SUM("Totalisationacompte"."mttotrsa") AS "Totalisationacompte__mttotrsa"'
//                 ),
//                 'recursive' => -1,
//                 'joins' => array(
//                     array(
//                         'table'      => 'identificationsflux',
//                         'alias'      => 'Identificationflux',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Totalisationacompte.identificationflux_id = Identificationflux.id' ),
//                     )
//                 ),
//                 'group' => array(
//                     'Totalisationacompte.type_totalisation',
//                     'Totalisationacompte.id'
//                 ),
//                 'order' => array( '"Totalisationacompte"."id" ASC' ),
//                 'conditions' => $conditions
//             );
// 
//             return $query;
// 
        }
    }

?>