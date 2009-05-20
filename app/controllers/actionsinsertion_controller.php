<?php
    class ActionsinsertionController extends AppController
    {

        var $name = 'Actionsinsertion';
        var $uses = array( 'Actioninsertion', 'Contratinsertion', 'Aidedirecte', 'Prestform', 'Option', 'Refpresta', 'Action' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'lib_action', $this->Option->lib_action() );
            $this->set( 'actions', $this->Action->grouplist( 'aide' ) );
            $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            $this->set( 'typo_aide', $this->Option->typo_aide() );

// 
//                 $this->set( 'typoscontrats', $this->Typocontrat->find( 'list' ) );
//                 $this->set( 'referents', $this->Referent->find( 'list' ) );
        }

        function index( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => -1
                )
            );

            // Si contrat n'existe pas -> 404
            if( empty( $contratinsertion ) ) {
                $this->cakeError( 'error404' );
            }

            $actionsinsertion = $this->Actioninsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Actioninsertion.contratinsertion_id' => $contratinsertion_id
                    ),
                    'recursive' => 2
                )
            );
 
//             foreach( $contratinsertion['Actioninsertion'] as $kai => $ai ) {
//                 foreach( $ai['Prestform'] as $kpf => $pf ) {
//                     $rp = $this->Refpresta->find(
//                         'first',
//                         array(
//                             'conditions' => array(
//                                 'Refpresta.id' => $pf['refpresta_id']
//                             ),
//                             'recursive' => -1
//                         )
//                     );
//                     $contratinsertion['Actioninsertion'][$kai]['Prestform'][$kpf]['Refpresta'] = $rp['Refpresta'];
//                 }
// 
//             }
// 
//             $actioninsertion = ( !empty( $contratinsertion['Actioninsertion'] ) ? array( 'Actioninsertion' => $contratinsertion['Actioninsertion'] ) : array() );

            $actions = $this->Action->find(
                'list',
                array(
                    'fields' => array(
                        'Action.code',
                        'Action.libelle'
                    )
                )
            );

            $this->set( 'actions', $actions );
            $this->set( 'actionsinsertion', $actionsinsertion );
            $this->set( 'contratinsertion_id', $contratinsertion_id);
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );


        }


        function edit( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $contratinsertion = $this->Actioninsertion->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => 2
                )
            );

            // Si action n'existe pas -> 404
            if( empty( $contratinsertion ) ) {
                $this->cakeError( 'error404' );
            }

            if( !empty( $this->data ) ) {

                if( $this->Actioninsertion->saveAll( $this->data ) ) {

                    $this->Session->setFlash( 'Enregistrement effectué' );
                    $this->redirect( array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Actioninsertion']['personne_id']) );
                }
            }
            else {

                $this->data = $contratinsertion;
            }
            $this->render( $this->action, null, 'add_edit' );



        }


//         function add( $contratinsertion_id = null ){
//            // TODO : vérif param
//            // Vérification du format de la variable
//             if( !valid_int( $contratinsertion_id ) ) {
//                 $this->cakeError( 'error404' );
//             }
// 
//             $contratinsertion = $this->Contratinsertion->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Contratinsertion.id' => $contratinsertion_id
//                     ),
//                     'recursive' => 2
//                 )
//             );
// 
//            // Si action n'existe pas -> 404
//             if( empty( $contratinsertion ) ) {
//                 $this->cakeError( 'error404' );
//             }
// 
// 
//            if( !empty( $this->data ) ) {
// 
//                 // FIXME pourquoi pas avec saveAll ?
//                 $this->Actioninsertion->set( $this->data['Actioninsertion'] );
//                 $this->Aidedirecte->set( $this->data['Aidedirecte'] );
// 
//                 $validates = $this->Actioninsertion->validates();
//                 $validates = $this->Aidedirecte->validates() && $validates;
// debug( $this->data );
//                 if( $validates ) {
//                     $this->Actioninsertion->begin();
//                     $this->Actioninsertion->set( $this->data );
//                     $saved = $this->Actioninsertion->save();
//                     foreach( $this->data['Aidedirecte'] as $index => $ad ) {
// // debug( $ad );
//                         // FIXME: new Ressourcemensuelle et new Detailressourcemensuelle
//                         $this->Aidedirecte->create();
//                         $ad['actioninsertion_id'] = $this->Actioninsertion->id;
//                         $saved = $this->Aidedirecte->save( $ad ) && $saved;
// 
// //                     $saved = $this->Actioninsertion->save( $this->data['Actioninsertion'] );
// //                     $saved = $this->Aidedirecte->save( $this->data['Aidedirecte'] )&& $saved;
//                     }
//                     if( $saved ) {
//                         $this->Actioninsertion->commit();
//                         $this->Session->setFlash( 'Enregistrement effectué' );
// 
//                     //FIXME: [0] grujage
//                     //$this->redirect( array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Actioninsertion']['personne_id']) );
//                     }
//                     else {
//                         $this->Actioninsertion->rollback();
//                     }
//                 }
//             }
// 
//             $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
//             $this->render( $this->action, null, 'add_edit' );
// 
//             }

}
?>