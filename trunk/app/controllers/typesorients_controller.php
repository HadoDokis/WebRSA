<?php
    class TypesorientsController extends AppController
    {

        var $name = 'Typesorients';
        var $uses = array( 'Typeorient', 'Structurereferente');
        var $helpers = array( 'Xform' );
        
        var $commeDroit = array(
			'add' => 'Typesorients:edit'
		);

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )
            );

			$this->set( 'occurences', $this->Typeorient->occurences() );
            $this->set( 'typesorients', $typesorients );
        }

        function add() {
            $this->set( 'options', $this->Typeorient->listOptions() );

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typesorients', $typesorients);

            $parentid = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient',
                    ),
                    'conditions' => array( 'Typeorient.parentid' => null )
                )
            );
            $this->set( 'parentid', $parentid );


            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }


	    $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typeorient_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typeorient_id ), 'error404' );

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typesorients', $typesorients);

            $parentid = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient',
                    ),
                    'conditions' => array(
						'Typeorient.parentid' => null,
						'Typeorient.id <>' => $typeorient_id,
					)
                )
            );
            $this->set( 'parentid', $parentid );

            $notif = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.modele_notif'
                    )
                )
            );

            $this->set( 'notif', $notif );

            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }
            else {
                $typeorient = $this->Typeorient->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typeorient.id' => $typeorient_id,
                        )
                    )
                );
                $this->data = $typeorient;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $typeorient_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typeorient_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typeorient = $this->Typeorient->find(
                'first',
                array( 'conditions' => array( 'Typeorient.id' => $typeorient_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typeorient_id ) ) {
                $this->cakeError( 'error404' );
            }

			$occurences = $this->Typeorient->occurences();
			$nbOccurences = Set::enum( $typeorient['Typeorient']['id'], $occurences );
			$nbOccurences = ( is_numeric( $nbOccurences ) ? $nbOccurences : 0 );

            // Tentative de suppression ... FIXME
            if( $nbOccurences != 0 ) {
                $this->Session->setFlash( 'Impossible de supprimer un type d\'orientation utilisé dans l\'application', 'flash/error' );
                $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typeorient->delete( array( 'Typeorient.id' => $typeorient_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
            }
        }
    }

?>
