<?php
    class DspfsController extends AppController
    {

        var $name = 'Dspfs';
        var $uses = array( 'Dspf', 'Nataccosocfam', 'Diflog', 'Personne', 'Option' , 'Nivetu', 'Difsoc', 'Nataccosocindi', 'Difdisp', 'Natmob', 'Foyer', 'Dspp');


        function beforeFilter() {
            parent::beforeFilter();
            // Personne
            $this->set( 'rolepers', $this->Option->rolepers() );

            // DSP personne
            $this->set( 'demarlog', $this->Option->demarlog() );
            $this->set( 'motidemrsa', $this->Option->motidemrsa() );
            $this->set( 'natlog', $this->Option->natlog() );
            $this->set( 'hispro', $this->Option->hispro() );
            $this->set( 'accoemploi', $this->Option->accoemploi() );
            $this->set( 'duractdomi', $this->Option->duractdomi() );
            //$this->set( 'nivetu', $this->Option->nivetu() );

            // DSP foyer
            $this->set( 'nataccosocfams', $this->Nataccosocfam->find( 'list' ) );
            $this->set( 'diflogs', $this->Diflog->find( 'list' ) );
            $this->set( 'difsocs', $this->Difsoc->find( 'list' ) );
            $this->set( 'nataccosocindis', $this->Nataccosocindi->find( 'list' ) );
            //$this->set( 'nivetu', $this->Option->nivetu() );
            $this->set( 'difdisps', $this->Difdisp->find( 'list' ) );
            $this->set( 'natmobs', $this->Natmob->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );
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
            foreach( array( 'DEM', 'CJT' ) as $rolepers ) {
                $dsp[$rolepers] = array();
                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Personne.foyer_id' => $foyer_id,
                            'Personne.rolepers' => $rolepers,
                        ),
                        'recursive' => -1
                    )
                ) ;
                if( !empty( $personne ) ) {
                    $dsp[$rolepers] = $personne;
                    $dsp_personne = $this->Dspp->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Dspp.personne_id' => $personne['Personne']['id']
                            ),
                            'recursive' => 2
                        )
                    ) ;
                    if( !empty( $dsp_personne ) ) {
                        $dsp[$rolepers] = array_merge( $dsp[$rolepers], $dsp_personne );
                    }
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
            $this->assert( valid_int( $foyer_id ), 'error404' );

            // Essai de sauvegarde
            if( !empty( $this->data ) && $this->Dspf->save( $this->data ) ) {
                $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                $this->redirect( array( 'controller' => 'dspfs', 'action' => 'view', $foyer_id ) );
            }

            $this->set( 'foyer_id', $foyer_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
            Édition des données socio-professionnelles d'un foyer
        */
        function edit( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'error404' );

            if( !empty( $this->data ) ) {
                if( $this->Dspf->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'dspfs', 'action' => 'view', $foyer_id ) );
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

            $this->set( 'foyer_id', $foyer_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>