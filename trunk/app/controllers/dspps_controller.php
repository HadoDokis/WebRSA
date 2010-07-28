<?php
    class DsppsController extends AppController
    {

        var $name = 'Dspps';
        var $uses = array( 'Dspp', 'Difsoc', 'Nataccosocindi', 'Difdisp', 'Natmob', 'Nivetu', 'Accoemploi', 'Personne', 'Option', 'Serviceinstructeur', 'Contratinsertion' );
        
		var $commeDroit = array(
			'add' => 'Dspps:edit'
		);

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'couvsoc', $this->Option->couvsoc() );
            $this->set( 'hispro', $this->Option->hispro() );
            $this->set( 'creareprisentrrech', $this->Option->creareprisentrrech() );
            $this->set( 'domideract', $this->Option->domideract() );
            $this->set( 'drorsarmiant', $this->Option->drorsarmiant() );
            $this->set( 'drorsarmianta2', $this->Option->drorsarmianta2() );
            $this->set( 'elopersdifdisp', $this->Option->elopersdifdisp() );
            $this->set( 'obstemploidifdisp', $this->Option->obstemploidifdisp() );
            $this->set( 'soutdemarsoc', $this->Option->soutdemarsoc() );
            $this->set( 'dipfra', $this->Option->dipfra() );

            $this->set( 'duractdomi', $this->Option->duractdomi() );
            // Données socioprofessionnelles personne
            $this->set( 'difsocs', $this->Difsoc->find( 'list' ) );
            $this->set( 'nataccosocindis', $this->Nataccosocindi->find( 'list' ) );
            $this->set('difdisps', $this->Difdisp->find( 'list' ) );
            $this->set( 'natmobs', $this->Natmob->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );

            $typeservices = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'typeservices', $typeservices );
            return $return;
        }

        /**
        *
        *
        *
        */

        function view( $personne_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $personne = $this->Personne->findById( $personne_id, null, null, -1 );
            $this->assert( !empty( $personne ), 'invalidParameter' );

            $dspp = $this->Dspp->findByPersonneId( $personne_id, null, null, 2 );


            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions'=> array(
                        'Contratinsertion.personne_id' => $personne_id
                    )
                )
            );

            if( !empty( $dspp ) ) {
                $dspp['Dspp']['diplomes'] = $contrat['Contratinsertion']['diplomes'];
            }

            $this->set( 'dspp', $dspp );
            $this->set( 'personne_id', $personne_id );
        }

        /**
        *
        * Ajout/création d'un dossier socio-professionnel pour la personne
        *
        */

        function add( $personne_id = null ) {

            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'view', $personne_id ) );
            }

            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Dspp->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Dspp->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Dspp->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Dspp->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Dspp->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dspps', 'action' => 'view', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }

            $personne = $this->Personne->find( 'first', array( 'conditions'=> array( 'Personne.id' => $personne_id ) ));
            $this->set(
                'foyer_id',
                $personne['Personne']['foyer_id']
            );

            $contratinsertion = $this->Contratinsertion->find( 'first', array( 'conditions'=> array( 'Contratinsertion.personne_id' => $personne_id ) ));

            if( !empty( $contratinsertion ) ){
                $this->data['Dspp']['diplomes'] = $contrat['Contratinsertion']['diplomes'];
            }

            $this->Dspp->commit();

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *
        *
        */

        function edit( $personne_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'view', $personne_id ) );
            }

            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Dspp->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Dspp->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Dspp->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Dspp->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Dspp->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dspps', 'action' => 'view', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {
                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions'=> array( 'Dspp.personne_id' => $personne_id )
                    )
                );
                $this->data = $dspp;


                $contrat = $this->Contratinsertion->find(
                    'first',
                    array(
                        'conditions'=> array(
                            'Contratinsertion.personne_id' => $personne_id
                        )
                    )
                );

                $personne = $this->Personne->find( 'first', array( 'conditions'=> array( 'Personne.id' => $personne_id ), 'recursive' => 1 ));
    // debug( $contrat );
                $this->data['Dspp']['diplomes'] = $contrat['Contratinsertion']['diplomes'];
            }

            $this->Dspp->commit();

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
