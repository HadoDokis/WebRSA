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
            'Default'
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Questionnaired1pdv93' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'index' => 'read',
			'view' => 'read',
		);

		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index( $personne_id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			// Remplit-on les conditions initiales ? / Messages à envoyer à l'utilisateur
			$messages = $this->Questionnaired1pdv93->messages( $personne_id );
			$add_enabled = $this->Questionnaired1pdv93->addEnabled( $messages );
			$this->set( compact( 'messages', 'add_enabled' ) );

			$querydata = array(
                'fields' => array(
                    'Personne.id',
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Questionnaired1pdv93.id',
                    'Questionnaired1pdv93.date_validation',
                    'Rendezvous.daterdv',
                    'Statutrdv.libelle',
                    'Historiquedroit.etatdosrsa',
                    'Historiquedroit.toppersdrodevorsa',
                    'Historiquedroit.created',
                    'Historiquedroit.modified',
                ),
                'joins' => array(
                    $this->Questionnaired1pdv93->join( 'Personne', array( 'type' => 'INNER' ) ),
                    $this->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
                    $this->Questionnaired1pdv93->Rendezvous->join( 'Statutrdv', array( 'type' => 'INNER' ) ),
                    $this->Questionnaired1pdv93->Personne->join( 'Historiquedroit', array( 'type' => 'LEFT OUTER', 'conditions' => array( 'Questionnaired1pdv93.created BETWEEN Historiquedroit.created AND Historiquedroit.modified' ) ) ),
                ),
                'contain' => false,
                'conditions' => array(
                    'Questionnaired1pdv93.personne_id' => $personne_id
                ),
                'order' => array(
                    'Questionnaired1pdv93.modified DESC'
                ),
                'limit' => 10
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

            $historiquesdroit = $this->Questionnaired1pdv93->Personne->Historiquedroit->find(
				'all',
				array(
					'conditions' => array(
						'Historiquedroit.personne_id' => $personne_id
					),
					'contain' => false
				)
			);

			$questionnairesd1pdvs93 = $this->Questionnaired1pdv93->find( 'all', $querydata );
            
            $options = Hash::merge(
				$this->Questionnaired1pdv93->enums(),
				$this->Questionnaired1pdv93->Situationallocataire->enums()
			);
            
            $optionsHisto = array(
				'Historiquedroit' => array(
					'etatdosrsa' => ClassRegistry::init( 'Option' )->etatdosrsa()
				)
			);
			$options = Set::merge( $options, $optionsHisto );

			$this->set( compact( 'personne_id', 'questionnairesd1pdvs93', 'personne', 'historiquesdroit', 'options' ) );
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

			$querydata = array(
				'fields' => array(
					'Situationallocataire.id'
				),
				'conditions' => array(
					'Questionnaired1pdv93.id' => $id
				),
				'contain' => array(
					'Situationallocataire'
				)
			);

			$questionnaired1pdv93 = $this->Questionnaired1pdv93->find( 'first', $querydata );
			if( empty( $questionnaired1pdv93 ) ) {
				throw new NotFoundException();
			}

			$this->Questionnaired1pdv93->begin();

			if( $this->Questionnaired1pdv93->Situationallocataire->delete( $questionnaired1pdv93['Situationallocataire']['id'], true ) ) {
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
		 * Formulaire d'ajout d'un élémént.
		 *
		 * @return void
		 */
		public function add( $personne_id ) {
			$messages = $this->Questionnaired1pdv93->messages( $personne_id );
			$add_enabled = $this->Questionnaired1pdv93->addEnabled( $messages );
			if( !$add_enabled ) {
				throw new InternalErrorException( "Impossible d'ajouter une formulaire D1 à cet allocataire cette année." );
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
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
			else {
				$this->request->data = $this->Questionnaired1pdv93->prepareFormData( $personne_id );
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

			$options = Hash::merge(
				$this->Questionnaired1pdv93->enums(),
				$this->Questionnaired1pdv93->Situationallocataire->enums()
			);

			$options['Questionnaired1pdv93']['rendezvous_id'] = $this->Questionnaired1pdv93->Personne->Rendezvous->findListPersonneId( $personne_id );
			$options['Situationallocataire']['nati'] = ClassRegistry::init( 'Tableausuivipdv93' )->nati;
			$options = $this->Questionnaired1pdv93->filterOptions( $options );

			$this->set( compact( 'personne_id', 'options', 'dossierMenu', 'personne' ) );
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

			$questionnaired1pdv93 = $this->Questionnaired1pdv93->completeDataForView( $questionnaired1pdv93 );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $questionnaired1pdv93['Personne']['id'] ) );

			$options = Hash::merge(
				$this->Questionnaired1pdv93->enums(),
				$this->Questionnaired1pdv93->Situationallocataire->enums()
			);

			$this->set( compact( 'questionnaired1pdv93', 'dossierMenu', 'options' ) );
		}
	}
?>
