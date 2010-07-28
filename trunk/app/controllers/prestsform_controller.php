<?php
    class PrestsformController extends AppController
    {

        var $name = 'Prestsform';
        var $uses = array( 'Actioninsertion', 'Contratinsertion', 'Aidedirecte', 'Prestform', 'Option', 'Refpresta', 'Action', 'Personne');
        
		var $commeDroit = array(
			'add' => 'Prestsform:edit'
		);

        function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'actions', $this->Action->grouplist( 'prestation' ) );// // 
        }

     function add( $contratinsertion_id = null ){
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
                    'recursive' => 1
                )
            );

            // Si action n'existe pas -> 404
            if( empty( $contratinsertion ) ) {
                $this->cakeError( 'error404' );
            }


           // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->data['Actioninsertion']['contratinsertion_id'] = $contratinsertion_id;
                $this->Actioninsertion->set( $this->data );
                $this->Prestform->set( $this->data['Prestform'] );
                $this->Refpresta->set( $this->data );


                $validates = $this->Actioninsertion->validates();
                $validates = $this->Prestform->validates() && $validates;
                $validates = $this->Refpresta->validates() && $validates;

                if( $validates ) {
                    $this->Actioninsertion->begin();
                    $saved = $this->Actioninsertion->save( $this->data );
                    $saved = $this->Refpresta->save( $this->data ) && $saved;

                    $this->data['Prestform']['refpresta_id'] = $this->Refpresta->id;
                    $this->data['Prestform']['actioninsertion_id'] = $this->Actioninsertion->id;
                    $saved = $this->Prestform->save( $this->data ) && $saved;


                    if( $saved ) {
                        $this->Actioninsertion->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );

                       // FIXME: 
                       $this->redirect( array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['id'] ) );
                    }
                    else {
                        $this->Actioninsertion->rollback();
                    }
                }

            }


            $this->data['Actioninsertion']['contratinsertion_id'] = $contratinsertion_id;
            $this->set( 'personne_id',  $contratinsertion['Contratinsertion']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );

        }


        function edit( $prestform_id = null ){
             // TODO : vérif param
             // Vérification du format de la variable
            if( !valid_int( $prestform_id ) ) {
                $this->cakeError( 'error404' );
            }

            $prestform = $this->Prestform->find(
                'first',
                array(
                    'conditions' => array(
                        'Prestform.id' => $prestform_id
                    ),
                    'recursive' => 2
                )
            );



            // Si action n'existe pas -> 404
            if( empty( $prestform ) ) {
                $this->cakeError( 'error404' );
            }


            if( !empty( $this->data ) ) {

                // FIXME pourquoi pas avec saveAll ?
                $this->Prestform->set( $this->data['Prestform'] );
                $this->Refpresta->set( $this->data['Refpresta'] );

                $validates = $this->Prestform->validates();
                $validates = $this->Refpresta->validates() && $validates;

                if( $validates ) {
                    $this->Prestform->begin();
                    $saved = $this->Prestform->save( $this->data['Prestform'] );
                    $saved = $this->Refpresta->save( $this->data['Refpresta'] ) && $saved;


                    if( $saved ) {
                        $this->Prestform->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success');

                    //FIXME: 
                       $this->redirect( array( 'controller' => 'actionsinsertion', 'action' => 'index', $prestform['Actioninsertion']['Contratinsertion']['id']) );
                    }
                    else {
                        $this->Prestform->rollback();
                    }
                }

            }
            else{
                $this->data = array(
                    'Prestform' => $prestform['Prestform'],
                    'Refpresta' => $prestform['Refpresta'],
                );
            }
                    // FIXME: [0] grujage
            $this->set( 'personne_id', $prestform['Actioninsertion']['Contratinsertion']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );
        }

}
