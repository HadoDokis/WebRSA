<?php
    class TyposcontratsController extends AppController
    {

        var $name = 'Typoscontrats';
        var $uses = array( 'Typocontrat', 'Contratinsertion');

        function index() {

            $typoscontrats = $this->Typocontrat->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typoscontrats', $typoscontrats);
        }

        function add() {
            $rang = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.id',
                        'Typocontrat.rang'
                    ),
                )
            );
            $this->set('rang', $rang);

            if( !empty( $this->data ) ) {
                if( $this->Typocontrat->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
                }
            }

            $this->set( 'rangs', array( 'premier' => 'Premier' ,'autre' => 'Autre' ) );
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typocontrat_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typocontrat_id ), 'error404' );

            $rang = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.id',
                        'Typocontrat.rang'
                    )
                )
            );

            if( !empty( $this->data ) ) {
                if( $this->Typocontrat->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
                }
            }
            else {
                $typocontrat = $this->Typocontrat->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typocontrat.id' => $typocontrat_id,
                        ),
                        'recursive' => -1
                    )
                );
                $this->data = $typocontrat;
                debug( $this->data );
            }

            $this->set( 'rangs', array( 'premier' => 'Premier' ,'autre' => 'Autre' ) );
            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>