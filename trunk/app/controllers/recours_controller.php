<?php 

    class RecoursController extends AppController{
        var $name = 'Recours';
        var $uses = array( 'Infofinanciere', 'Option', 'Avispcgdroitrsa' );

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'typecommission', $this->Option->typecommission() );
            $this->set( 'decision', $this->Option->decision() );
            $this->set( 'motif', $this->Option->motif() );
        }

        function gracieux( $dossier_rsa_id = null ){

            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $gracieux = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.dossier_rsa_id' => $dossier_rsa_id
                    ),
                'recursive' => -1
                )
            );

            $avispcg = $this->Avispcgdroitrsa->findByDossierRsaId( $dossier_rsa_id );
//             $this->assert( !empty( $gracieux ), 'error404' );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'avispcg', $avispcg );
            $this->set( 'gracieux', $gracieux );
        }

        function contentieux( $dossier_rsa_id = null ){

            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $contentieux = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.dossier_rsa_id' => $dossier_rsa_id
                    ),
                'recursive' => -1
                )
            );
//             $this->assert( !empty( $contentieux ), 'error404' );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'contentieux', $contentieux );
        }
    }
?>