<?php
    class ContratsinsertionController extends AppController
    {

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Referent', 'Personne', 'Dossier', 'Option', 'Structurereferente', 'Typocontrat', 'Nivetu', 'Dspp', 'Typeorient');


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'referents', $this->Referent->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );
        }

        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            //$this->Contratinsertion->recursive = -1;
            $contratsinsertion = $this->Contratinsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404

            $this->set( 'contratsinsertion', $contratsinsertion );
            $this->set( 'personne_id', $personne_id );

        }

        function view( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404

            $this->set( 'contratinsertion', $contratinsertion );
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

        }

        /**
            Ajout
        */
        function add( $personne_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );


            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.id',
                        'Typocontrat.lib_typo'
                    ),
                    'order'  => array( 'Typocontrat.id ASC' )

                )
            );
            $this->set( 'tc', $tc );

        // Essai de sauvegarde
            if( !empty( $this->data ) && $this->Contratinsertion->saveAll( $this->data ) ) {
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;
                $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index/', $personne_id ) );
            }
            else{
                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Dspp.personne_id' => $personne_id
                        )
                    )
                );

            }


            $personne = $this->Personne->find( 'first', array( 'conditions'=> array( 'Personne.id' => $personne_id ) ));
            $this->set(
                'foyer_id',
                $personne['Personne']['foyer_id']
            );


            $conditions = array( 'Personne.id' => $personne_id );
            $personne = $this->Personne->find( 'first', array( 'conditions' => $conditions ) );

            // Assignation à la vue
            $this->set( 'personne', $personne );
           // $this->data = array_merge( $this->data, $personne );
            $this->set( 'personne_id', $personne_id );

            $this->render( $this->action, null, 'add_edit' );
        }


        function edit( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            // TODO -> 404
            $contratinsertion = $this->Contratinsertion->find(
            'first',
            array(
                'conditions' => array(
                'Contratinsertion.id' => $contratinsertion_id
                )
            )
            );

            if (empty($contratinsertion)){
                $this->cakeError( 'error404' );
            }

              $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

            if( !empty( $this->data ) ) {

                if( $this->Contratinsertion->saveAll( $this->data ) ) {

                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
                }
            }
            else {

                $this->data = $contratinsertion;
            }
            $this->render( $this->action, null, 'add_edit' );
        }


        function delete( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array( 'conditions' => array( 'Contratinsertion.id' => $contratinsertion_id )
                )
            );

            // Mauvais paramètre
            if( empty( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Contratinsertion->delete( array( 'Contratinsertion.id' => $contratinsertion_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée' );
                //$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
            }
        }
}
?>