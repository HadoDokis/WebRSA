<?php 

    class RecoursController extends AppController{
        var $name = 'Recours';
        var $uses = array( 'Infofinanciere', 'Option' );


        function gracieux( $dossier_rsa_id = null ){

            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $recours = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.dossier_rsa_id' => $dossier_rsa_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $recours ), 'error404' );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'recours', $recours );
        }

        function contentieux( $dossier_rsa_id = null ){

            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $recours = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.dossier_rsa_id' => $dossier_rsa_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $recours ), 'error404' );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'recours', $recours );
        }
    }
?>