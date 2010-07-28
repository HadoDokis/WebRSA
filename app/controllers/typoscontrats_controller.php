<?php
    class TyposcontratsController extends AppController
    {

        var $name = 'Typoscontrats';
        var $uses = array( 'Typocontrat', 'Contratinsertion');
        var $helpers = array( 'Xform' );
        
        var $commeDroit = array(
			'add' => 'Typoscontrats:edit'
		);

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
            $typoscontrats = $this->Typocontrat->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typoscontrats', $typoscontrats);
        }

        function add() {


            if( !empty( $this->data ) ) {
                if( $this->Typocontrat->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typocontrat_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typocontrat_id ), 'error404' );


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
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $typocontrat_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typocontrat_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typocontrat = $this->Typocontrat->find(
                'first',
                array( 'conditions' => array( 'Typocontrat.id' => $typocontrat_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typocontrat_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typocontrat->delete( array( 'Typocontrat.id' => $typocontrat_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
            }
        }
    }

?>
