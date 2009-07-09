<?php
    class PersonnesController extends AppController
    {
        var $name = 'Personnes';
        var $uses = array( 'Personne', 'Option' );

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'nationalite', $this->Option->nationalite() );
            $this->set( 'typedtnai', $this->Option->typedtnai() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'sexe', $this->Option->sexe() );
            return $return;
        }

        /**
        *
        *   Voir les personnes d'un foyer
        *
        */

        function index( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            // Recherche des personnes du foyer
            $personnes = $this->Personne->find(
                'all',
                array(
                    'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
                    'recursive' => 2
                )
            );

            // Assignations à la vue
            $this->set( 'foyer_id', $foyer_id );
            $this->set( 'personnes', $personnes );
        }

        /**
        *
        *   Voir une personne en particulier
        *
        */

        function view( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            // Recherche de la personne
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array( 'Personne.id' => $id ),
                    'recursive' => 2
                )
            );

            // Mauvais paramètre
            $this->assert( !empty( $personne ), 'invalidParameter' );

            // Assignation à la vue
            $this->set( 'personne', $personne );
        }

        /**
        *
        *   Ajout d'une personne au foyer
        *
        */

        function add( $foyer_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Personne->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Personne->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            if( !empty( $this->data ) ) {
                if( ( $this->data['Prestation']['rolepers'] == 'DEM' ) || ( $this->data['Prestation']['rolepers'] == 'CJT' ) ) {
                    $this->data['Orientstruct'] = array( 'statut_orient' => 'Non orienté' );
                    $this->data['Prestation']['toppersdrodevorsa'] = true;
                }
                if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        // FIXME: mettre dans un afterSave (mais ça pose des problèmes)
                        // FIXME: valeur de retour
                        $thisPersonne = $this->Personne->findById( $this->Personne->id, null, null, -1 );
                        $this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

                        $this->Jetons->release( $dossier_id );
                        $this->Personne->commit();
                        $this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
                        $this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {
                $roles = $this->Personne->find(
                    'all',
                    array(
                        'fields' => array(
                            'Personne.id',
                            'Prestation.rolepers',
                        ),
                        'conditions' => array(
                            'Personne.foyer_id' => $foyer_id,
                            'Prestation.rolepers' => array( 'DEM', 'CJT' )
                        ),
                    )
                );
                $roles = Set::extract( '/Prestation/rolepers', $roles );

                // On ne fait apparaître les roles de demandeur et de conjoint que
                // si ceux-ci n'existent pas encore dans le foyer
                $rolepersPermis = $this->Option->rolepers();
                foreach( $rolepersPermis as $key => $rPP ) {
                    if( in_array( $key, $roles ) ) {
                        unset( $rolepersPermis[$key] );
                    }
                }
                $this->set( 'rolepers', $rolepersPermis );
            }

            $this->set( 'foyer_id', $foyer_id );
            $this->data['Personne']['foyer_id'] = $foyer_id;

            $this->Personne->commit();
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *   Éditer une personne spécifique d'un foyer
        *
        */

        function edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            $dossier_id = $this->Personne->dossierId( $id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Personne->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Personne->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        // FIXME: mettre dans un afterSave (mais ça pose des problèmes)
                        // FIXME: valeur de retour
                        $thisPersonne = $this->Personne->findById( $this->Personne->id, null, null, -1 );
                        $this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

                        $this->Jetons->release( $dossier_id );
                        $this->Personne->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'personnes','action' => 'index', $this->data['Personne']['foyer_id'] ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Afficage des données
            else {
                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array( 'Personne.id' => $id ),
                        'recursive' => 2
                    )
                );
                $this->assert( !empty( $personne ), 'invalidParameter' );

                // Assignation au formulaire
                $this->data = $personne;
            }

            $this->Personne->commit();
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>