<?php
	/**
	 * Code source de la classe AdressesfoyersController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AdressesfoyersController permet de lister, voir, ajouter et supprimer des adresses à un foyer RSA.
	 *
	 * @package app.Controller
	 */
	class AdressesfoyersController extends AppController
	{
		public $name = 'Adressesfoyers';

		public $uses = array( 'Adressefoyer', 'Option' );

		public $components = array( 'Jetons2' );

		public $commeDroit = array(
			'view' => 'Adressesfoyers:index',
			'add' => 'Adressesfoyers:edit'
		);

		/**
		 * Commun à toutes les fonctions
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'rgadr', $this->Option->rgadr() );
			$this->set( 'typeadr', $this->Option->typeadr() );
			$this->set( 'typevoie', $this->Option->typevoie() );
		}

		/**
		 * Liste des adresses d'un foyer.
		 *
		 * @param integer $foyer_id L'id technique du Foyer pour lequel on veut les adresses.
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
		 * Visualisation d'une adresse spécifique.
		 *
		 * @param integer $id  L'id technique de l'enregistrement de la table adressesfoyers
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
		 * Ajouter une adresse à un foyer
		 *
		 * @param integer $foyer_id L'id technique du foyer auquel ajouter l'adresse.
		 * @return void
		 */
		public function add( $foyer_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$dossier_id = $this->Adressefoyer->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}


			// Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				if( $this->Adressefoyer->saveAll( $this->request->data, array( 'validate' => 'only' ) ) ) {
					$this->Adressefoyer->begin();

					if( $this->Adressefoyer->saveNouvelleAdresse( $this->request->data ) ) {
						$this->Adressefoyer->commit();
						$this->Jetons2->release( $dossier_id );
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
			$this->render( 'add_edit' );
		}

		/**
		 * Modification d'une adresse du foyer.
		 *
		 * @param integer $id L'id technique dans la table adressesfoyers.
		 * @return void
		 */
		public function edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$dossier_id = $this->Adressefoyer->dossierId( $id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Adressefoyer->id = $id;
				$foyer_id = $this->Adressefoyer->field( 'foyer_id' );
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}

			// Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Adressefoyer->begin();

				if( $this->Adressefoyer->saveAll( $this->request->data, array( 'validate' => 'only' ) ) ) {
					$this->Adressefoyer->begin();

					if( $this->Adressefoyer->saveAll( $this->request->data, array( 'atomic' => false ) ) ) {
						$this->Adressefoyer->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $this->request->data['Adressefoyer']['foyer_id'] ) );
					}
					else {
						$this->Adressefoyer->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Adressefoyer->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
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
				$this->request->data = $adresse;
			}

			$this->set( 'urlmenu', '/adressesfoyers/index/'.$this->request->data['Adressefoyer']['foyer_id'] );
			$this->render( 'add_edit' );
		}
	}
?>