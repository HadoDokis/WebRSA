<?php
    class IdentificationsfluxController  extends AppController
    {
        var $name = 'Identificationsflux';
        var $uses = array( 'Identificationflux', 'Option', 'Totalisationacompte' );


        function beforeFilter() {
            parent::beforeFilter();
//             $this->set( 'type_allocation', $this->Option->type_allocation() );
//             $this->set( 'natpfcre', $this->Option->natpfcre() );
//             $this->set( 'typeopecompta', $this->Option->typeopecompta() );
//             $this->set( 'sensopecompta', $this->Option->sensopecompta() );
        }


        function index( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            // Recherche des adresses du foyer
            $identflux = $this->Identificationflux->find(
                'all',
                array(
                    'conditions' => array( 'Identificationflux.id' => $id ),
                    'recursive' => 1
                )
            );

            // Assignations à la vue
            $this->set( 'identflux', $identflux );
        }

//         function view( $id = null ) {
//             // Vérification du format de la variable
//             $this->assert( valid_int( $infofinanciere_id ), 'error404' );
// 
//             $infofinanciere = $this->Infofinanciere->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Infofinanciere.id' => $infofinanciere_id
//                     ),
//                 'recursive' => -1
//                 )
// 
//             );
// 
//             $this->assert( !empty( $infofinanciere ), 'error404' );
// 
//             // Assignations à la vue
//             $this->set( 'dossier_id', $infofinanciere['Infofinanciere']['dossier_id'] );
// 
//             $this->set( 'infofinanciere', $infofinanciere );
// 
//         }


}
