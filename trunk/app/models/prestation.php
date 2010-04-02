<?php
    class Prestation extends AppModel
    {
        var $name = 'Prestation';
        var $useTable = 'prestations';

        //---------------------------------------------------------------------

        var $belongsTo = array(
            'Personne'
        );

        //---------------------------------------------------------------------

        var $validate = array(
            // Role personne
            'rolepers' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
        );

        //*********************************************************************

//         function dossierId( $prestation_id ) {
// //             $this->unbindModelAll();
// //             $this->Personne->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
// //             $this->Personne->bindModel(
// //                 array(
// //                     'hasOne' => array(
// //                         'Prestation' => array(
// //                             'foreignKey' => false,
// //                             'conditions' => array(
// //                                 'Prestation.personne_id = Personne.id',
// //                                 'Prestation.rolepers' => array( 'DEM', 'CJT' ),
// //                                 'Prestation.natprest' => array( 'RSA' )
// //                             )
// //                         )
// //                     )
// //                 )
// //             );
//             $personne = $this->Personne->find( 'first', array( 'conditions' => array( 'Prestation.id' => $prestation_id ) ) );
//             if( !empty( $personne ) ) {
//                 return $personne['Foyer']['dossier_rsa_id'];
//             }
//             else {
//                 return null;
//             }
//         }
    }
?>