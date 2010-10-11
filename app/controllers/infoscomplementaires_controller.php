<?php
    class InfoscomplementairesController extends AppController {

        var $name = 'Infoscomplementaires';
        var $uses = array( 'Personne', 'Creancealimentaire', 'Titresejour', 'Activite', 'Allocationsoutienfamilial', 'Option', 'Dossier' );
        var $helpers = array( 'Theme' );


        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'act', $this->Option->act() );
            $this->set( 'reg', $this->Option->reg() );
            $this->set( 'paysact', $this->Option->paysact() );
            $this->set( 'orioblalim', $this->Option->orioblalim() );
            $this->set( 'etatcrealim', $this->Option->etatcrealim() );
            $this->set( 'verspa', $this->Option->verspa() );
            $this->set( 'topjugpa', $this->Option->topjugpa() );
            $this->set( 'motidiscrealim', $this->Option->motidiscrealim() );
            $this->set( 'engproccrealim', $this->Option->engproccrealim() );
            $this->set( 'topdemdisproccrealim', $this->Option->topdemdisproccrealim() );
            $this->set( 'sitasf', $this->Option->sitasf() );
            $this->set( 'parassoasf', $this->Option->parassoasf() );

            return $return;
        }


        /**
        *
        */

        function view( $id = null ) {
            $this->assert( valid_int( $id ), 'invalidParameter' );
            /** Tables necessaire à l'ecran de synthèse

                OK -> Dossier
                OK -> Foyer

                OK -> Creance
                OK -> Dossiercaf
                OK -> Personne (DEM/CJT)
                    OK -> allocationssoutienfamilial
                    OK -> activites
                    OK -> dossierscaf (premier/dernier)
                    OK -> titressejours
                    OK ->  creancesalimentaires 
            */
            $details = array();

            $tDossier = $this->Dossier->findById( $id, null, null, -1 );
            $details = Set::merge( $details, $tDossier );

            $tFoyer = $this->Dossier->Foyer->findByDossierId( $id, null, null, -1 );
            $details = Set::merge( $details, $tFoyer );

            /**
                Personnes
            */
            $bindPrestation = $this->Personne->hasOne['Prestation'];
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Prestation' => $bindPrestation ) ) );
            $personnesFoyer = $this->Personne->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $tFoyer['Foyer']['id'],
                        'Prestation.rolepers' => array( 'DEM', 'CJT' )
                    ),
                    'recursive' => 0
                )
            );

            $roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
            foreach( $roles as $index => $role ) {
                ///Créances alimentaires
                $tCreancealimentaire = $this->Creancealimentaire->find(
                    'first',
                    array(
                        'conditions' => array( 'Creancealimentaire.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
                        'recursive' => -1,
                        'order' => 'Creancealimentaire.ddcrealim DESC',
                    )
                );
                $personnesFoyer[$index]['Creancealimentaire'] = $tCreancealimentaire['Creancealimentaire'];

                ///Titres séjour
                $tTitresejour = $this->Titresejour->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Titresejour.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Titresejour.ddtitsej DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Titresejour'] = $tTitresejour['Titresejour'];

                ///Activités
                $tActivite = $this->Activite->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Activite.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Activite.ddact DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Activite'] = $tActivite['Activite'];

                ///Allocation au soutien familial
                $tAllocationsoutienfamilial = $this->Allocationsoutienfamilial->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Allocationsoutienfamilial.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Allocationsoutienfamilial.ddasf DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Allocationsoutienfamilial'] = $tAllocationsoutienfamilial['Allocationsoutienfamilial'];


                $details[$role] = $personnesFoyer[$index];
            }
// debug($details);
// echo '<pre>';
// var_dump( $details );
// echo '</pre>';
            $this->set( 'details', $details );
        }
    }
?>