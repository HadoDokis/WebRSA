<?php
	/**
	 * Code source de la classe Questionnairesd1pdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Questionnairesd1pdvs93Controller ...
	 *
	 * @package app.Controller
	 */
	class Questionnairesd1pdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Questionnairesd1pdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'DossiersMenus',
			'Jetons2'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Questionnaired1pdv93' );

		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index( $personne_id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->paginate = array(
				'Questionnaired1pdv93' => array(
					'fields' => array_merge(
						$this->Questionnaired1pdv93->fields(),
						$this->Questionnaired1pdv93->Situationallocataire->fields(),
						$this->Questionnaired1pdv93->Rendezvous->fields(),
						$this->Questionnaired1pdv93->Rendezvous->Statutrdv->fields(),
						$this->Questionnaired1pdv93->Rendezvous->Typerdv->fields()
					),
					'joins' => array(
						$this->Questionnaired1pdv93->join( 'Situationallocataire', array( 'type' => 'INNER' ) ),
						$this->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
						$this->Questionnaired1pdv93->Rendezvous->join( 'Statutrdv', array( 'type' => 'INNER' ) ),
						$this->Questionnaired1pdv93->Rendezvous->join( 'Typerdv', array( 'type' => 'INNER' ) ),
					),
					'contain' => false,
					'conditions' => array(
						'Questionnaired1pdv93.personne_id' => $personne_id
					),
					'order' => array(
						'Questionnaired1pdv93.modified DESC'
					),
					'limit' => 10
				)
			);

			$personne = $this->Questionnaired1pdv93->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);

			$questionnairesd1pdvs93 = $this->paginate( 'Questionnaired1pdv93' );
			$this->set( compact( 'personne_id', 'questionnairesd1pdvs93', 'personne' ) );
		}

		/**
		 * Formulaire d'ajout d'un élémént.
		 *
		 * @return void
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Suppression d'un questionnaire D1, de la Situationallocataire associée
		 * et redirection vers l'index.
		 *
		 * FIXME: ne supprime pas la Situationallocataire liée
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$personne_id = $this->Questionnaired1pdv93->personneId( $id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->Questionnaired1pdv93->begin();

			if( $this->Questionnaired1pdv93->delete( $id, true ) ) {
				$this->Questionnaired1pdv93->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Questionnaired1pdv93->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( array( 'action' => 'index', $personne_id ) );
		}

		/**
		 * Formulaire de modification d'un <élément>.
		 *
		 * @throws NotFoundException
		 */
		public function edit( $id = null ) {
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else {
				$personne_id = $this->Questionnaired1pdv93->personneId( $id );
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				if( isset( $this->request->data['Validate'] ) ) {
					$data = $this->request->data;
					$data['Questionnaired1pdv93']['valide'] = '1';
					$this->request->data = $data;
				}

				$this->Questionnaired1pdv93->begin();

				$result = $this->Questionnaired1pdv93->saveAssociated(
					$this->request->data,
					array(
						'validate' => 'first',
						'atomic' => false
					)
				);

				if( $this->Questionnaired1pdv93->saveResultAsBool($result) ) {
					$this->Questionnaired1pdv93->commit();
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Questionnaired1pdv93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Questionnaired1pdv93->find(
					'first',
					array(
						'conditions' => array(
							"{$this->modelClass}.id" => $id
						),
						'contain' => array(
							'Situationallocataire'
						)
					)
				);

				if( empty( $this->request->data  ) ) {
					throw new NotFoundException();
				}
			}
			else {
				$this->request->data = $this->Questionnaired1pdv93->prepareFormDataAddEdit( null, $personne_id ); // FIXME
			}

			$personne = $this->Questionnaired1pdv93->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);

			$options = $this->Questionnaired1pdv93->enums();
			$options['Questionnaired1pdv93']['rendezvous_id'] = $this->Questionnaired1pdv93->Personne->Rendezvous->findListPersonneId( $personne_id );

			$this->set( compact( 'personne_id', 'options', 'dossierMenu', 'personne' ) );
			$this->render( 'edit' );
		}

		/**
		 *
		 * @param integer $id
		 * @throws error404Exception
		 */
		public function view( $id ) {
			$questionnaired1pdv93 = $this->Questionnaired1pdv93->find(
				'first',
				array(
					'conditions' => array(
						'Questionnaired1pdv93.id' => $id
					),
					'contain' => array(
						'Personne',
						'Rendezvous',
						'Situationallocataire',
					)
				)
			);

			if( empty( $questionnaired1pdv93 ) ) {
				throw new error404Exception();
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $questionnaired1pdv93['Personne']['id'] ) );

			$options = Hash::merge(
				$this->Questionnaired1pdv93->enums(),
				$this->Questionnaired1pdv93->Situationallocataire->enums()
			);

			$this->set( compact( 'questionnaired1pdv93', 'dossierMenu', 'options' ) );
		}
	}
?>
