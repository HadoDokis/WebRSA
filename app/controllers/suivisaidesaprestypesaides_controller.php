<?php

    class SuivisaidesaprestypesaidesController extends AppController 
    {
        var $name = 'Suivisaidesaprestypesaides';
        var $uses = array( 'Suiviaideapretypeaide', 'Suiviaideapre', 'Option', 'Apre' );
        var $helpers = array( 'Xform' );

        function beforeFilter() {
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'personnessuivis', $this->Suiviaideapre->find( 'list' ) );
            $this->set( 'aidesApres', $this->Apre->aidesApre );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
        }


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
            }

            $suivisaidesaprestypesaides = $this->Suiviaideapretypeaide->find( 'all', array( 'recursive' => -1 ) );
            $this->set('suivisaidesaprestypesaides', $suivisaidesaprestypesaides );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/
        function _add_edit( $id = null ) {

            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( !empty( $this->data ) ) {
                $this->data = Set::extract( $this->data, '/Suiviaideapretypeaide' );
                if( $this->Suiviaideapretypeaide->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'index' ) );
                }
            }
            else {
                $suiviaideapretypeaide = $this->Suiviaideapretypeaide->find( 'all' );
                $this->data = array( 'Suiviaideapretypeaide' => Set::classicExtract( $suiviaideapretypeaide, '{n}.Suiviaideapretypeaide' ) );
                /*foreach( $suiviaideapretypeaide as $i => $Datas ) {
                    $this->data[$Datas] = $suiviaideapretypeaide;
                    debug($suiviaideapretypeaide);
                }*/
//                 $suiviaideapretypeaide = $this->Suiviaideapretypeaide->find( 'all' );
//                 debug($suiviaideapretypeaide);
//                 $this->data = Set::extract( $suiviaideapretypeaide, '/Suiviaideapretypeaide' );
//                 $suiviaideapretypeaide = $this->Suiviaideapretypeaide->find(
//                     'first',
//                     array(
//                         'conditions' => array(
//                             'Suiviaideapretypeaide' => Set::extract( $this->data, '/Suiviaideapretypeaide' ),
//                         )
//                     )
//                 );
//                 $this->data = $suiviaideapretypeaide;
            }

            $this->render( $this->action, null, 'add_edit' );
        }


//         function delete( $suiviaideapretypeaide_id = null ) {
//             // Vérification du format de la variable
//             if( !valid_int( $suiviaideapretypeaide_id ) ) {
//                 $this->cakeError( 'error404' );
//             }
// 
//             // Recherche de la personne
//             $suiviaideapretypeaide = $this->Suiviaideapretypeaide->find(
//                 'first',
//                 array( 'conditions' => array( 'Suiviaideapretypeaide.id' => $suiviaideapretypeaide_id )
//                 )
//             );
// 
//             // Mauvais paramètre
//             if( empty( $suiviaideapretypeaide_id ) ) {
//                 $this->cakeError( 'error404' );
//             }
// 
//             // Tentative de suppression ... FIXME
//             if( $this->Suiviaideapretypeaide->delete( array( 'Suiviaideapretypeaide.id' => $suiviaideapretypeaide_id ) ) ) {
//                 $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
//                 $this->redirect( array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'index' ) );
//             }
//         }

    }
?>