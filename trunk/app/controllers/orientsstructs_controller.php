<?php
    class OrientsstructsController extends AppController
    {

        var $name = 'Orientsstructs';
        var $uses = array( 'Orientstruct',  'Option' , 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente', 'Demandereorient', 'Pdf', 'Referent' );
        var $helpers = array( 'Default' );
        var $components = array( 'Gedooo' );



        protected function _setOptions() {
            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
            $this->set( 'referents', $this->Referent->listOptions() );
            $this->set( 'typesorients', $this->Typeorient->listOptions() );
            $this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $options = array();
//             foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
//                 $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
//             }
//             $this->set( compact( 'options' ) );
        }


        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
//             $this->set( 'pays', $this->Option->pays() );
//             $this->set( 'qual', $this->Option->qual() );
//             $this->set( 'rolepers', $this->Option->rolepers() );
//             $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );

//             $this->set( 'structs', $this->Structurereferente->list1Options() );

            $options = array();
            foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
            }
            /*foreach( array( 'Demandereorient' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }*/
            $this->set( compact( 'options' ) );

            return $return;
        }

        /**
        *
        *
        *
        */

        function index( $personne_id = null ){
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );
/*
            $orientstructs = $this->Orientstruct->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => 2
                )
            );

            $dossier_rsa_id = Set::extract( $orientstructs, '0.Personne.Foyer.dossier_rsa_id' );*/

            $orientstructs = $this->Orientstruct->find(
                'all',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id
                    ),
                    'recursive' => 0
                )
            );

			foreach( $orientstructs as $key => $orientstruct ) {
				$orientstruct[$this->Orientstruct->alias]['imprime'] = $this->Pdf->find(
					'count',
					array(
						'conditions' => array(
							'Pdf.fk_value' => $orientstruct[$this->Orientstruct->alias]['id'],
							'Pdf.modele = \'Orientstruct\''
						)
					)
				);
				$orientstructs[$key] = $orientstruct;
			}

            $dossier_rsa_id = Set::extract( $orientstructs, '0.Personne.Foyer.dossier_rsa_id' );

			$this->Demandereorient->unbindModelAll();
			$this->Demandereorient->bindModel(
				array(
					'belongsTo' => array(
						'Seanceep',
						'VxTypeorient' => array(
							'className' => 'Typeorient',
							'foreignKey' => 'vx_typeorient_id'
						),
						'VxStructurereferente' => array(
							'className' => 'Structurereferente',
							'foreignKey' => 'vx_structurereferente_id'
						),
						'VxReferent' => array(
							'className' => 'Referent',
							'foreignKey' => 'vx_referent_id'
						),
					)
				)
			);
            $demandesreorients = $this->Demandereorient->find(
                'all',
                array(
                    'conditions' => array(
                        'Demandereorient.personne_id' => $personne_id
                    ),
					'recursive' => 0
                )
            );
            $this->set( 'demandesreorients', $demandesreorients );

            /*if( !empty( $orientstructs ) ) {
// debug($orientstructs);
                foreach( $orientstructs as $orientstruct ) {
                    $demandesreorients = $this->Demandereorient->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Demandereorient.orientstruct_id' => Set::classicExtract( $orientstruct, 'Orientstruct.id' )
                            )
                        )
                    );
                    $this->set( 'demandesreorients', $demandesreorients );
                }
            }*/

            $this->set( 'droitsouverts', $this->Dossier->Situationdossierrsa->droitsOuverts( $dossier_rsa_id ) );
            $this->set( 'orientstructs', $orientstructs );
            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );
        }

        /**
        *
        *
        *
        */

        function add( $personne_id = null ) {
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $personne_id ) );
            }

            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Orientstruct->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Orientstruct->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


            if( !empty( $this->data ) ) {
// debug( $this->data );
                $this->Orientstruct->set( $this->data );
//                 $this->Typeorient->set( $this->data );
//                 $this->Structurereferente->set( $this->data );

                $validates = $this->Orientstruct->validates();
//                 $validates = $this->Typeorient->validates() && $validates;
//                 $validates = $this->Structurereferente->validates() && $validates;


                if( $validates ) {
                    // Orientation
                    $this->Orientstruct->create();

                    $this->data['Orientstruct']['personne_id'] = $personne_id;
                    $this->data['Orientstruct']['valid_cg'] = true;
                    $this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['statut_orient'] = 'Orienté';

                    $saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data );
                    $saved = $this->Orientstruct->save( $this->data['Orientstruct'] ) && $saved;
					$saved = $this->Gedooo->mkOrientstructPdf( $this->Orientstruct->getLastInsertId() ) && $saved;

                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Orientstruct->rollback();
                    }
                }
            }
            else {
                $personne = $this->Personne->findByid( $personne_id, null, null, 0 );
                $this->data['Calculdroitrsa'] = $personne['Calculdroitrsa'];
            }

            //$this->Orientstruct->commit();

            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *
        *
        */

        function edit( $orientstruct_id = null ) {
            $this->assert( valid_int( $orientstruct_id ), 'invalidParameter' );

            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $orientstruct_id = $this->Orientstruct->field( 'personne_id', array( 'id' => $orientstruct_id ) );
                $this->redirect( array( 'action' => 'index', $orientstruct_id ) );
            }

            $orientstruct = $this->Orientstruct->findById( $orientstruct_id, null, null, 2 );
            $this->assert( !empty( $orientstruct ), 'invalidParameter' );

            $dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Orientstruct->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Orientstruct->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->Orientstruct->set( $this->data );
                $this->Orientstruct->Personne->Calculdroitrsa->set( $this->data );
                $valid = $this->Orientstruct->Personne->Calculdroitrsa->validates();
                $valid = $this->Orientstruct->validates() && $valid;

                if( $valid ) {
                    if( $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data )
						&& $this->Orientstruct->save( $this->data )
						&& $this->Gedooo->mkOrientstructPdf( $this->Orientstruct->id )
					) {
                        $this->Jetons->release( $dossier_id );
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
                    }
                    else {
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Afficage des données
            else {
                // Assignation au formulaire*
                $this->data = Set::merge( array( 'Orientstruct' => $orientstruct['Orientstruct'] ), array( 'Calculdroitrsa' => $orientstruct['Personne']['Calculdroitrsa'] ) );

            }

            $this->Orientstruct->commit();
            $this->_setOptions();
            $this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
