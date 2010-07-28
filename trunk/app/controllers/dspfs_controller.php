<?php
    class DspfsController extends AppController
    {

        var $name = 'Dspfs';
        var $uses = array( 'Dspf', 'Nataccosocfam', 'Diflog', 'Personne', 'Option' , 'Nivetu', 'Difsoc', 'Nataccosocindi', 'Difdisp', 'Natmob', 'Foyer', 'Dspp', 'Accoemploi');
        
		var $commeDroit = array(
			'add' => 'Dspfs:edit'
		);

        function beforeFilter() {
            parent::beforeFilter();
            // Personne
            $this->set( 'rolepers', $this->Option->rolepers() );

            // DSP personne
            $this->set( 'demarlog', $this->Option->demarlog() );
            $this->set( 'motidemrsa', $this->Option->motidemrsa() );
            $this->set( 'natlog', $this->Option->natlog() );
            $this->set( 'hispro', $this->Option->hispro() );
            $this->set( 'couvsoc', $this->Option->couvsoc() );
            $this->set( 'creareprisentrrech', $this->Option->creareprisentrrech() );
            $this->set( 'domideract', $this->Option->domideract() );
            $this->set( 'drorsarmiant', $this->Option->drorsarmiant() );
            $this->set( 'drorsarmianta2', $this->Option->drorsarmianta2() );
            $this->set( 'elopersdifdisp', $this->Option->elopersdifdisp() );
            $this->set( 'obstemploidifdisp', $this->Option->obstemploidifdisp() );
            $this->set( 'soutdemarsoc', $this->Option->soutdemarsoc() );
            $this->set( 'duractdomi', $this->Option->duractdomi() );
            $this->set( 'accosocfam', $this->Option->accosocfam() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'typeocclog', $this->Option->typeocclog() );

            // DSP foyer
            $this->set( 'nataccosocfams', $this->Nataccosocfam->find( 'list' ) );
            $this->set( 'diflogs', $this->Diflog->find( 'list' ) );
            $this->set( 'difsocs', $this->Difsoc->find( 'list' ) );
            $this->set( 'nataccosocindis', $this->Nataccosocindi->find( 'list' ) );
            $this->set( 'difdisps', $this->Difdisp->find( 'list' ) );
            $this->set( 'natmobs', $this->Natmob->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
        }


        function view( $foyer_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'error404' );

            $dsp = array();

            // DSP foyer
            $dsp['foyer'] = $this->Dspf->find(
                'first',
                array(
                    'conditions' => array(
                        'Dspf.foyer_id' => $foyer_id
                    )
                )
            ) ;

            // Ajout des DSP demandeur et conjoint
            $personnesFoyer = $this->Personne->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $foyer_id,
                        'Prestation.rolepers' => array( 'DEM', 'CJT' )
                    ),
                    'recursive' => 0
                )
            );

            foreach( $personnesFoyer as $personneFoyer ) {
                $dsp[$personneFoyer['Prestation']['rolepers']] = $personneFoyer;
                $dsp_personne = $this->Dspp->findByPersonneId( $personneFoyer['Personne']['id'], null, null, 2 );
                if( !empty( $dsp_personne ) ) {
                    $dsp[$personneFoyer['Prestation']['rolepers']] = array_merge( $dsp[$personneFoyer['Prestation']['rolepers']], $dsp_personne );
                }
                else {
                    unset( $dsp[$personneFoyer['Prestation']['rolepers']]['Dspp'] );
                }
            }

            $this->set( 'dsp', $dsp );
            $this->set( 'foyer_id', $foyer_id );

        }
        /**
            Création de données socio-professionnelles pour le foyer
        */
        function add( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Dspf->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Dspf->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Dspf->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Dspf->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Dspf->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dspfs', 'action' => 'view', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }

            $foyer = $this->Foyer->find(
                'first',
                array(
                    'conditions'=> array(
                        'Foyer.id' => $foyer_id
                    )
                )
            );
            $this->set( 'foyer', $foyer );

            $this->set( 'dossier_rsa_id', $foyer['Foyer']['dossier_rsa_id'] );

            $this->Dspf->commit();

            $this->set( 'foyer_id', $foyer_id );

            $this->render( $this->action, null, 'add_edit' );
        }

        /**
            Édition des données socio-professionnelles d'un foyer
        */
        function edit( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Dspf->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Dspf->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Dspf->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Dspf->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Dspf->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dspfs', 'action' => 'view', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {
                $dspf = $this->Dspf->find(
                    'first',
                    array(
                        'conditions'=> array(
                            'Dspf.foyer_id' => $foyer_id
                        )
                    )
                );
                $this->assert( !empty( $dspf ), 'error404' );
                $this->data = $dspf;
            }

            $this->Dspf->commit();

            $this->set( 'foyer_id', $foyer_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
