<?php
    class ApresComitesapresController extends AppController
    {

        var $name = 'ApresComitesapres';
        var $uses = array( 'ApreComiteapre', 'Apre', 'Comiteapre' );
        var $helpers = array( 'Xform' );

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'apre', $this->Apre->find( 'list' ) );
            $this->set( 'apres', $this->Apre->find( 'all' ) );
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

        function _add_edit( $id = null ){
            if( $this->action == 'add' ) {
                $comiteapre_id = $id;
                $nbrComites = $this->Comiteapre->find( 'count', array( 'conditions' => array( 'Comiteapre.id' => $comiteapre_id ), 'recursive' => -1 ) );
                $this->assert( ( $nbrComites == 1 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $comiteapre_id = $id;
                $aprecomite = $this->ApreComiteapre->find(
                    'all',
                    array(
                        'conditions' => array(
                            'ApreComiteapre.comiteapre_id' => $comiteapre_id
                        )
                    )
                );
                $this->assert( !empty( $aprecomite ), 'invalidParameter' );
            }

            if( !empty( $this->data ) ) {
                foreach( $this->data['Apre']['Apre'] as $i => $apreId ) {
                    if( empty( $apreId ) ) {
                        unset( $this->data['Apre']['Apre'][$i] );
                    }
                }
// debug( $this->data );
                if( $this->Comiteapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'view', $comiteapre_id ) );
                }
            }
            else {
                if( $this->action == 'edit' ) {
                    $this->data = array(
                        'Comiteapre' => array(
                            'id' => $comiteapre_id,
                        ),
                        'Apre' => array(
                            'Apre' => Set::extract( $aprecomite, '/ApreComiteapre/apre_id' )
                        )
                    );
                }
                else {
                    $this->data['Comiteapre']['id'] = $comiteapre_id;
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>