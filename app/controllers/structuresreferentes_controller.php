<?php
    class StructuresreferentesController extends AppController
    {

        var $name = 'Structuresreferentes';
        var $uses = array( 'Structurereferente', 'Referent', 'Orientstruct', 'Typeorient', 'Zonegeographique');

        function index() {

            $structuresreferentes = $this->Structurereferente->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('structuresreferentes', $structuresreferentes);
        }

        function add() {
    
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
			'Typeorient.id',
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
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $structurereferente_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $structurereferente_id ), 'error404' );

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

    }

?>