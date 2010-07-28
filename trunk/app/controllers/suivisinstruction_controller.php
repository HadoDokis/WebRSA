<?php
    class SuivisinstructionController  extends AppController
    {
        var $name = 'Suivisinstruction';
        var $uses = array( 'Suiviinstruction', 'Option', 'Dossier', 'Serviceinstructeur' );
        
		var $commeDroit = array(
			'view' => 'Suivisinstruction:index'
		);

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'suiirsa', $this->Option->suiirsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );

        }


        function index( $dossier_rsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );

            // Recherche des adresses du foyer
            $suivisinstruction = $this->Suiviinstruction->find(
                'all',
                array(
                    'conditions' => array( 'Suiviinstruction.dossier_rsa_id' => $dossier_rsa_id ),
                    'recursive' => -1
                )
            );

            // Assignations à la vue
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'suivisinstruction', $suivisinstruction );
        }


        function view( $suiviinstruction_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $suiviinstruction_id ), 'error404' );

            $suiviinstruction = $this->Suiviinstruction->find(
                'first',
                array(
                    'conditions' => array(
                        'Suiviinstruction.id' => $suiviinstruction_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $suiviinstruction ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $suiviinstruction['Suiviinstruction']['dossier_rsa_id'] );

            $this->set( 'suiviinstruction', $suiviinstruction );

        }


}
