<?php
	class AdressesfoyersController extends AppController
	{
		public $name = 'Adressesfoyers';
		public $uses = array( 'Adressefoyer', 'Option' );

		public $commeDroit = array(
			'view' => 'Adressefoyers:index',
			'add' => 'Adressefoyers:edit'
		);

		/**
			Commun à toutes les fonctions
		*/
		public function beforeFilter() {
			$return = parent::beforeFilter();
			// FIXME: pourquoi ? à priori parce que notre table a des underscore dans son nom!
			// INFO: http://book.cakephp.org/view/24/Model-and-Database-Conventions pour corriger mes erreurs
			$this->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'rgadr', $this->Option->rgadr() );
			$this->set( 'typeadr', $this->Option->typeadr() );
			$this->set( 'typevoie', $this->Option->typevoie() );

			return $return;
		}

		/**
			Voir les adresses d'un foyer
		*/
		public function index( $foyer_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			// Recherche des adresses du foyer
			$adresses = $this->Adressefoyer->find(
				'all',
				array(
					'conditions' => array( 'Adressefoyer.foyer_id' => $foyer_id ),
					'contain' => array(
						'Adresse'
					)
				)
			);
			// Assignations à la vue
			$this->set( 'foyer_id', $foyer_id );
			$this->set( 'adresses', $adresses );
		}

		/**
			Voir une adresse spécifique d'un foyer
		*/
		public function view( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Recherche de l'adresse
			$adresse = $this->Adressefoyer->find(
				'first',
				array(
					'conditions' => array( 'Adressefoyer.id' => $id ),
					'contain' => array(
						'Adresse'
					)
				)
			);

			// Mauvais paramètre
			$this->assert( !empty( $adresse ), 'invalidParameter' );

			// Assignation à la vue
			$this->set( 'adresse', $adresse );
			$this->set( 'urlmenu', '/adressesfoyers/index/'.$adresse['Adressefoyer']['foyer_id'] );
		}

		/**
			Éditer une adresse spécifique d'un foyer
		*/
		public function edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$dossier_id = $this->Adressefoyer->dossierId( $id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Adressefoyer->begin();
			$test = ( $this->Jetons->check( $dossier_id ) && $this->Jetons->get( $dossier_id ) );
			if( !$test ) {
				$this->Adressefoyer->rollback();
			}
			else {
				$this->Adressefoyer->commit();
			}
			$this->assert( $test, 'lockedDossier' );

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				if( $this->Adressefoyer->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
					$this->Adressefoyer->begin();

					if( $this->Adressefoyer->saveAll( $this->data, array( 'atomic' => false ) ) ) {
						$this->Jetons->release( $dossier_id );
						$this->Adressefoyer->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $this->data['Adressefoyer']['foyer_id'] ) );
					}
					else {
						$this->Adressefoyer->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			// Afficage des données
			else {
				$adresse = $this->Adressefoyer->find(
					'first',
					array(
						'conditions' => array( 'Adressefoyer.id' => $id ),
						'contain' => array(
							'Adresse'
						)
					)
				);

				// Mauvais paramètre
				$this->assert( !empty( $adresse ), 'invalidParameter' );

				// Assignation au formulaire
				$this->data = $adresse;
			}

			$this->set( 'urlmenu', '/adressesfoyers/index/'.$this->data['Adressefoyer']['foyer_id'] );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/
		public function add( $foyer_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$dossier_id = $this->Adressefoyer->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Adressefoyer->begin();
			$test = ( $this->Jetons->check( $dossier_id ) && $this->Jetons->get( $dossier_id ) );
			if( !$test ) {
				$this->Adressefoyer->rollback();
			}
			else {
				$this->Adressefoyer->commit();
			}
			$this->assert( $test, 'lockedDossier' );

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				if( $this->Adressefoyer->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
					$this->Adressefoyer->begin();

					if( $this->Adressefoyer->saveNouvelleAdresse( $this->data ) ) {
						$this->Jetons->release( $dossier_id );
						$this->Adressefoyer->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $foyer_id ) );
					}
					else {
						$this->Adressefoyer->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}

			// Assignation à la vue
			$this->set( 'foyer_id', $foyer_id );
			$this->render( $this->action, null, 'add_edit' );
		}
	}
?>