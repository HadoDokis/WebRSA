<?php
    class PersonnesController extends AppController
    {
        var $name = 'Personnes';
        var $uses = array( 'Personne', 'Option', 'Grossesse', 'Foyer' );

		var $commeDroit = array(
			'view' => 'Personnes:index',
			'add' => 'Personnes:edit'
		);

        /**
        *
        *
        *
        */

        protected function _setOptions() {
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'nationalite', $this->Option->nationalite() );
            $this->set( 'typedtnai', $this->Option->typedtnai() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'sexe', $this->Option->sexe() );
            $this->set( 'sitfam', $this->Option->sitfam() );

        }

        /**
        *
        *   Voir les personnes d'un foyer
        *
        */

        function index( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			// On n'a besoin que de la prestation RSA
			$prestationRsa = $this->Personne->hasOne['Prestation'];
			$this->Personne->unbindModelAll();
			$this->Personne->bindModel( array( 'hasOne' => array( 'Prestation' => $prestationRsa ) ) );

            // Recherche des personnes du foyer
            $personnes = $this->Personne->find(
                'all',
                array(
                    'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
                    'recursive' => 0
                )
            );

            // Assignations à la vue
            $this->_setOptions();
            $this->set( 'foyer_id', $foyer_id );
            $this->set( 'personnes', $personnes );
        }

        /**
        *
        *   Voir une personne en particulier
        *
        */

        function view( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            // Recherche de la personne
            $grossesse = $this->Grossesse->find(
                'first',
                array(
                    'conditions' => array( 'Grossesse.id' => $id ),
                    'recursive' => -1
                )
            );

			// On n'a besoin que de la prestation RSA
			$prestationRsa = $this->Personne->hasOne['Prestation'];
			$this->Personne->unbindModelAll();
			$this->Personne->bindModel( array( 'hasOne' => array( 'Prestation' => $prestationRsa ) ) );

            // Recherche de la personne
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array( 'Personne.id' => $id ),
                    'recursive' => 0
                )
            );

            // Mauvais paramètre
            $this->assert( !empty( $personne ), 'invalidParameter' );

            // Assignation à la vue
            $this->_setOptions();
            $this->set( 'grossesse', $grossesse );
            $this->set( 'personne', $personne );
        }

        /**
        *
        *   Ajout d'une personne au foyer
        *
        */

        function add( $foyer_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $personne = $this->Personne->Foyer->find(
                'first',
                array(
                    'fields' => array(
                        'Foyer.sitfam'
                    ),
                    'conditions' => array(
                        'Foyer.id' => $foyer_id
                    ),
                    'recursive' => -1
                )
            );

            $this->Personne->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Personne->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            if( !empty( $this->data ) ) {
                if( ( $this->data['Prestation']['rolepers'] == 'DEM' ) || ( $this->data['Prestation']['rolepers'] == 'CJT' ) ) {
                    $this->data['Calculdroitrsa']['toppersdrodevorsa'] = true;
                }

                if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        // FIXME: mettre dans un afterSave (mais ça pose des problèmes)
                        // FIXME: valeur de retour
                        $thisPersonne = $this->Personne->findById( $this->Personne->id, null, null, -1 );

                        $this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

                        $this->Jetons->release( $dossier_id );
                        $this->Personne->commit();
                        $this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
                        $this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {
                $roles = $this->Personne->find(
                    'all',
                    array(
                        'fields' => array(
                            'Personne.id',
                            'Prestation.rolepers',
                        ),
                        'conditions' => array(
                            'Personne.foyer_id' => $foyer_id,
                            'Prestation.rolepers' => array( 'DEM', 'CJT' )
                        ),
                        'recursive' => 0
                    )
                );
                $roles = Set::extract( '/Prestation/rolepers', $roles );

                // On ne fait apparaître les roles de demandeur et de conjoint que
                // si ceux-ci n'existent pas encore dans le foyer
                $rolepersPermis = $this->Option->rolepers();
                foreach( $rolepersPermis as $key => $rPP ) {
                    if( in_array( $key, $roles ) ) {
                        unset( $rolepersPermis[$key] );
                    }
                }
                $this->set( 'rolepers', $rolepersPermis );
            }

            $this->set( 'foyer_id', $foyer_id );
            $this->data['Personne']['foyer_id'] = $foyer_id;
            $this->set( 'personne', $personne );
            $this->_setOptions();
            $this->Personne->commit();
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *   Éditer une personne spécifique d'un foyer
        *
        */

        function edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            $dossier_id = $this->Personne->dossierId( $id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Personne->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Personne->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        // FIXME: mettre dans un afterSave (mais ça pose des problèmes)
                        // FIXME: valeur de retour
                        $thisPersonne = $this->Personne->findById( $this->Personne->id, null, null, -1 );
                        $this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

                        $this->Jetons->release( $dossier_id );
                        $this->Personne->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'personnes','action' => 'index', $this->data['Personne']['foyer_id'] ) );
                    }
                    else {
						$this->Personne->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
				else {
					$this->Personne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
            }
            // Afficage des données
            else {
				// On n'a besoin que de la prestation RSA et du Foyer
				$prestationRsa = $this->Personne->hasOne['Prestation'];
				$this->Personne->unbindModelAll();
				$this->Personne->bindModel(
					array(
						'belongsTo' => array( 'Foyer' ),
						'hasOne' => array( 'Prestation' => $prestationRsa )
					)
				);

                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array( 'Personne.id' => $id ),
                        'recursive' => 0
                    )
                );

                $this->assert( !empty( $personne ), 'invalidParameter' );

                $sitfam = $this->Option->sitfam();
                $situationfamiliale = Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ),  $sitfam );
                $this->set( 'situationfamiliale', $situationfamiliale );

                // Assignation au formulaire
                $this->data = $personne;
                $this->set( 'personne', $personne );
				$this->Personne->commit();
            }

            $this->_setOptions();
            $this->Personne->commit();
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
