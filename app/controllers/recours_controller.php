<?php 

    class RecoursController extends AppController{
        var $name = 'Recours';
        var $uses = array( 'Infofinanciere', 'Option', 'Avispcgdroitrsa' );

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'decisionrecours', $this->Option->decisionrecours() );
            $this->set( 'motifrecours', $this->Option->motifrecours() );
        }

        function gracieux( $dossier_id = null ){

            $this->assert( valid_int( $dossier_id ), 'invalidParameter' );

            $gracieux = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.dossier_id' => $dossier_id
                    ),
                'recursive' => -1
                )
            );

            $avispcg = $this->Avispcgdroitrsa->findByDossierId( $dossier_id );
//             $this->assert( !empty( $gracieux ), 'error404' );

            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'avispcg', $avispcg );
            $this->set( 'gracieux', $gracieux );
        }

        function contentieux( $dossier_id = null ){

            $this->assert( valid_int( $dossier_id ), 'invalidParameter' );

            $contentieux = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.dossier_id' => $dossier_id
                    ),
                'recursive' => -1
                )
            );
//             $this->assert( !empty( $contentieux ), 'error404' );

            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'contentieux', $contentieux );
        }
    }
?>