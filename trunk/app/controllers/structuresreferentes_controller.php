<?php
    class StructuresreferentesController extends AppController
    {

        var $name = 'Structuresreferentes';
        var $uses = array( 'Structurereferente', 'Referent', 'Orientstruct', 'Typeorient', 'Zonegeographique', 'Apre', 'Option' );
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Structuresreferentes:edit'
		);

         function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'typevoie', $this->Option->typevoie() );

            $optionsradio = $this->Structurereferente->allEnumLists();
            $this->set( 'optionsradio', $optionsradio );
        }


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $typeorient = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.lib_type_orient'
                    )
                )
            );
            $this->set( 'typeorient', $typeorient );

            $structuresreferentes = $this->Structurereferente->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('structuresreferentes', $structuresreferentes);
        }

        function add() {
            $this->set( 'options', $this->Typeorient->listOptions() );
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $type = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
//                         'Typeorient.parentid',
                        'Typeorient.id',
                        'Typeorient.lib_type_orient',
                    )
                )
            );
            $this->set( 'type', $type );



            if( !empty( $this->data ) ) {
                if( $this->Structurereferente->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $structurereferente_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $structurereferente_id ), 'error404' );
            $this->set( 'options', $this->Typeorient->listOptions() );
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $type = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.lib_type_orient'
                    )
                )
            );
            $this->set( 'type', $type );

            if( !empty( $this->data ) ) {
                if( $this->Structurereferente->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
                }
            }
            else {
                $structurereferente = $this->Structurereferente->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Structurereferente.id' => $structurereferente_id,
                        )
                    )
                );
                $this->data = $structurereferente;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $structurereferente_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $structurereferente_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $structurereferente = $this->Structurereferente->find(
                'first',
                array( 'conditions' => array( 'Structurereferente.id' => $structurereferente_id )
                )
            );

            // Mauvais paramètre
            if( empty( $structurereferente_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Structurereferente->delete( array( 'Structurereferente.id' => $structurereferente_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
            }
        }
    }

?>
