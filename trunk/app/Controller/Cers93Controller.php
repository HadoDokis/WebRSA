<?php
	/**
	 * Code source de la classe Cers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Cers93Controller.
	 *
	 * @package app.controllers
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
		 */
		public function index( $personne_id ) {
			$this->paginate = array(
				'Contratinsertion' => array(
					'contain' => array(
						'Cer93'
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'limit' => 10
				)
			);

			$varname = Inflector::tableize( 'Contratinsertion' );
			$results = $this->paginate( $this->Cer93->Contratinsertion );
			debug( $results );
			$this->set( $varname, $results );
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
		 * @throws BadRequestException
		 */
		public function edit( $id = null ) {
			if( $this->action == 'add' ) {
				$dossier_id = $this->Cer93->Contratinsertion->Personne->dossierId( $id );
			}
			else {
				$dossier_id = $this->Cer93->Contratinsertion->dossierId( $id );
			}
			$this->Jetons2->get( $dossier_id );

			if( !empty( $this->request->data ) ) {

				$this->Cer93->Contratinsertion->begin();

				// FIXME: en Cake 2 l'enregistrement multiple devrait mieux fonctionner
				if( $this->Cer93->Contratinsertion->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->Cer93->Contratinsertion->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
//					$this->redirect( array( 'action' => 'index', $this->request->data['Contratinsertion']['personne_id'] ) );
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
							$this->Cer93->fields(),
							$this->Cer93->Etatcivilcer93->fields()
						),
						'conditions' => array(
							'Contratinsertion.id' => $id
						),
						'joins' => array(
							$this->Cer93->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
							$this->Cer93->join( 'Etatcivilcer93', array( 'type' => 'LEFT OUTER' ) )
						),
						'contain' => false
					)
				);

				// FIXME: après avoir fait le passage des ancienns données, on peut remettre cette règle
				/*if( empty( $this->request->data  ) ) {
					$this->cakeError( 'error404' );
				}*/
			}

			$this->render( 'edit' );
		}

		/**
		 * Suppression d'un <élément> et redirection vers l'index.
		 *
		 * @param integer $id
		 */
		/*public function delete( $id ) {
			$this->Cer93->begin();

			if( $this->Cer93->delete( $id ) ) {
				$this->Cer93->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Cer93->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( array( 'action' => 'index' ) );
		}*/
	}
?>
