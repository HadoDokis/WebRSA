<?php
	/**
	 * Code source de la classe Cers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cers93Controller permet la gestion des CER du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class Cers93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Cers93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Jetons2' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array();

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cer93' );

		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire auquel le CER est attaché.
		 * @return void
		 * @throws NotFoundException
		 */
		public function index( $personne_id = null ) {
			if( !$this->Cer93->Contratinsertion->Personne->exists( $personne_id ) ) {
				throw new NotFoundException();
			}

			$querydata = array(
				'contain' => array(
					'Cer93'
				),
				'conditions' => array(
					'Contratinsertion.personne_id' => $personne_id
				)
			);

			$results = $this->Cer93->Contratinsertion->find( 'all', $querydata );

			$this->set( 'cers93', $results );
			$this->set( 'personne_id', $personne_id );
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
		 * Formulaire de modification d'un <élément>.
		 *
		 * @return void
		 * @throws NotFoundException
		 */
		public function edit( $id = null ) {
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else {
				$this->Cer93->Contratinsertion->id = $id;
				$personne_id = $this->Cer93->Contratinsertion->field( 'personne_id' );
			}

			// Le dossier auquel appartient la personne
			$dossier_id = $this->Cer93->Contratinsertion->Personne->dossierId( $personne_id );

			// On s'assure que l'id passé en paramètre et le dossier lié existent bien
			if( empty( $personne_id ) || empty( $dossier_id ) ) {
				throw new NotFoundException();
			}

			// Tentative d'acquisition du jeton sur le dossier
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Cer93->Contratinsertion->begin();

				// Sinon, ça pose des problèmes lors du add car la valeur n'existe pas encore
				$this->Cer93->unsetValidationRule( 'contratinsertion_id', 'notEmpty' );

				if( $this->Cer93->Contratinsertion->saveAssociated( $this->request->data, array( 'validate' => 'first', 'atomic' => false, 'deep' => true ) ) ) {
					$this->Cer93->Contratinsertion->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Cer93->Contratinsertion->find(
					'first',
					array(
						'fields' => array_merge(
							$this->Cer93->Contratinsertion->fields(),
							$this->Cer93->fields()
						),
						'conditions' => array(
							'Contratinsertion.id' => $id
						),
						'joins' => array(
							$this->Cer93->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
						),
						'contain' => false
					)
				);
			}

			// Lecture des informations non modifiables
			$personne = $this->Cer93->Contratinsertion->Personne->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Cer93->Contratinsertion->Personne->fields(),
						$this->Cer93->Contratinsertion->Personne->Foyer->fields(),
						$this->Cer93->Contratinsertion->Personne->Foyer->Dossier->fields()
					),
					'joins' => array(
						$this->Cer93->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
						$this->Cer93->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);

			// Options
			$options = array(
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Cer93->Contratinsertion->Structurereferente->listOptions()
				)
			);

			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'options', 'personne' ) );
			$this->render( 'edit' );
		}
	}
?>
